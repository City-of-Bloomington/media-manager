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
			$media = new Media($id);
		}
		catch (\Exception $e) {
			$_SESSION['errorMessages'][] = $e;
			header('Location: '.BASE_URL);
			exit();
		}
		return $media;
	}

	public function index()
	{
	}

	public function view()
	{
		$media = $this->loadMedia($_GET['media_id']);
		$this->template->blocks[] = new Block('media/view.inc', ['media'=>$media]);
	}

	/**
	 * Create and cache a resized image file
	 *
	 * @param REQUEST media_id
	 * @param REQUEST size
	 */
	public function resize()
	{
		$this->template->setFilename('media');
		try {
			$media = new Media($_REQUEST['media_id']);
			$size = !empty($_REQUEST['size']) ? (int)$_REQUEST['size'] : null;
			$this->template->blocks[] = new Block(
				'media/image.inc',
				array('media'=>$media, 'size'=>$size)
			);
		}
		catch (Exception $e) {
			header('HTTP/1.1 404 Not Found', true, 404);
		}
	}

	public function update()
	{
		$media = $this->loadMedia($_REQUEST['media_id']);

		if (isset($_POST['title'])) {
			try {
				$media->handleUpdate($_POST);
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
