<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Media;
use Application\Models\UploadDirectory;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class UploadsController extends Controller
{
	public function index()
	{
		// Single file uploads get saved to the database right away
		// Send the user to the edit page immediately, so they can
		// enter all the metadata for the file
		if (isset($_FILES['mediafile'])) {
			try {
				$media = new Media();
				$media->setFile($_FILES['mediafile']);
				$media->save();

				header('Location: '.BASE_URL.'/media/update?media_id='.$media->getId());
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		// Multi-file uploads are still going to be coming in one file at a time.
		// However, each file needs to be saved in the UploadDirectory until the user
		// is ready to import them.
		if (isset($_FILES['batchFile'])) {
			try {
				$uploads = new UploadDirectory($_SESSION['USER']);
				$uploads->add($_FILES['batchFile']);
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		if ($this->template->outputFormat == 'html') {
			$this->template->blocks[] = new Block('uploads/form.inc');
			$this->template->blocks[] = new Block('uploads/files.inc', ['user'=>$_SESSION['USER']]);
		}
	}

	public function thumbnail()
	{
		$this->template->setFilename('media');
		$this->template->blocks[] = new Block('uploads/thumbnail.inc');
	}

	public function import()
	{
		if (isset($_POST['import']) && count($_POST['import'])) {
			try {
				$directory = new UploadDirectory($_SESSION['USER']);
				$directory->import($_POST['import']);
				header('Location: '.BASE_URL);
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
	}
}
