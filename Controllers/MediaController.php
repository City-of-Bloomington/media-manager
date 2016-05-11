<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Media;
use Application\Models\MediaTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class MediaController extends Controller
{
	private function loadMedia($id)
	{
        try { return new Media($id); }
        catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	/**
	 * @param Media $media
	 * @param int $derivative Size for derivative
	 * @return string Classname of media type
	 */
	private static function classForMediaDerivative(Media $media, $derivative)
	{
        $type = $media->getMedia_type();
        $class = 'Application\\Models\\'.ucfirst($type);
        if ($class::isValidSize($derivative)) {
            return $class;
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
	}


	public function index(array $params)
	{
	}

	public function view(array $params)
	{
		$media = $this->loadMedia($_GET['id']);
		return $media
            ? new \Application\Views\Media\InfoView(['media'=>$media])
            : new \Application\Views\NotFoundView();
	}

	/**
	 * Create, cache, and serve a resized image file
	 *
	 * @param REQUEST media_id
	 * @param REQUEST derivative
	 */
	public function derivative(array $params)
	{
        $media = $this->loadMedia($params['media_id']);
        if ($media) {
            $derivative = !empty($params['derivative']) ? $params['derivative'] : null;
            $class = self::classForMediaDerivative($media, $derivative);

            return new \Application\Views\Media\RenderView(['media'=>$media, 'derivative'=>$derivative]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
	}

	/**
	 * Url to upload a custom derivative file for a media
	 *
	 * @param REQUEST media_id
	 * @param REQUEST derivative
	 * @param FILES derivativeFile
	 */
	public function saveDerivative(array $params)
	{
		if (   empty($_FILES['derivativeFile']['tmp_name'])
            || empty($_REQUEST['media_id'])
            || empty($_REQUEST['derivative'])) {

            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }

        $media = $this->loadMedia($_REQUEST['media_id']);
        if ($media) {
            $derivative = !empty($_REQUEST['derivative']) ? $_REQUEST['derivative'] : null;
            $class = self::classForMediaDerivative($media, $derivative);

            $class::saveDerivative($media->getFullPath(), $derivative, $_FILES['derivativeFile']['tmp_name']);

            return new \Application\Views\Media\RenderView(['media'=>$media, 'derivative'=>$derivative]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
	}

	public function update(array $params)
	{
		$media = $this->loadMedia($_REQUEST['id']);
		if ($media) {
            if (isset($_POST['title'])) {
                try {
                    $media->handleUpdate($_POST);
                    if (isset($_FILES['mediafile']) && is_uploaded_file($_FILES['mediafile']['tmp_name'])) {
                        $media->setFile($_FILES['mediafile']);
                    }
                    $media->save();

                    $url = self::generateUrl('media.view', ['id'=>$media->getId()]);
                    header('Location: '.$url);
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }
            return new \Application\Views\Media\UpdateView(['media'=>$media]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
	}

	public function delete(array $params)
	{
		$media = $this->loadMedia($_GET['id']);
		if ($media) {
            $media->delete();

            header('Location: '.self::generateUrl('home'));
            exit();
        }
	}
}
