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
		try {
            if (!$id) { throw new \Exception('media/unknown'); }
			$media = new Media($id);
		}
		catch (\Exception $e) {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
			return null;
		}
		return $media;
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


	public function index()
	{
	}

	public function view()
	{
		$media = $this->loadMedia($_GET['media_id']);
		if ($media) {
            $this->template->blocks[] = new Block('media/view.inc', ['media'=>$media]);
        }
	}

	/**
	 * Create, cache, and serve a resized image file
	 *
	 * @param REQUEST media_id
	 * @param REQUEST derivative
	 */
	public function derivative()
	{
		$this->template->setFilename('media');

        $media = $this->loadMedia($_REQUEST['media_id']);
        if ($media) {
            $derivative = !empty($_REQUEST['derivative']) ? $_REQUEST['derivative'] : null;
            $class = self::classForMediaDerivative($media, $derivative);

            $this->template->blocks[] = new Block(
                "media/download.inc",
                ['media'=>$media, 'derivative'=>$derivative]
            );
        }
	}

	/**
	 * Url to upload a custom derivative file for a media
	 *
	 * @param REQUEST media_id
	 * @param REQUEST derivative
	 * @param FILES derivativeFile
	 */
	public function saveDerivative()
	{
		if (   empty($_FILES['derivativeFile']['tmp_name'])
            || empty($_REQUEST['media_id'])
            || empty($_REQUEST['derivative'])) {

            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }

		$this->template->setFilename('media');

        $media = $this->loadMedia($_REQUEST['media_id']);
        if ($media) {
            $derivative = !empty($_REQUEST['derivative']) ? $_REQUEST['derivative'] : null;
            $class = self::classForMediaDerivative($media, $derivative);

            $class::saveDerivative($media->getFullPath(), $derivative, $_FILES['derivativeFile']['tmp_name']);
            $this->template->blocks[] = new Block(
                "media/download.inc",
                ['media'=>$media, 'derivative'=>$derivative]
            );
        }
	}

	public function update()
	{
		$media = $this->loadMedia($_REQUEST['media_id']);
		if ($media) {
            if (isset($_POST['title'])) {
                try {
                    $media->handleUpdate($_POST);
                    if (isset($_FILES['mediafile']) && is_uploaded_file($_FILES['mediafile']['tmp_name'])) {
                        $media->setFile($_FILES['mediafile']);
                    }
                    $media->save();
                    header('Location: '.BASE_URL.'/media/view?media_id='.$media->getId());
                    exit();
                }
                catch (\Exception $e) {
                    $_SESSION['errorMessages'][] = $e;
                }
            }
            $this->template->blocks[] = new Block('media/updateForm.inc', ['media'=>$media]);
        }
	}

	public function delete()
	{
		$media = $this->loadMedia($_GET['media_id']);
		if ($media) {
            $media->delete();
            header('Location: '.BASE_URL);
            exit();
        }
	}
}
