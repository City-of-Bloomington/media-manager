<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Derivative;
use Application\Models\DerivativesTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class DerivativesController extends Controller
{
	public function index()
	{
		$table = new DerivativesTable();
		$derivatives = $table->find();
		$this->template->blocks[] = new Block('derivatives/list.inc', ['derivatives'=>$derivatives]);
	}

	public function update()
	{
		if (!empty($_REQUEST['derivative_id'])) {
			try {
				$derivative = new Derivative($_REQUEST['derivative_id']);
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
				header('Location: '.BASE_URL.'/derivatives');
				exit();
			}
		}
		else {
			$derivative = new Derivative();
		}

		if (isset($_POST['size'])) {
			try {
				$derivative->handleUpdate($_POST);
				$derivative->save();
				header('Location: '.BASE_URL.'/derivatives');
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		$this->template->blocks[] = new Block('derivatives/updateForm.inc', ['derivative'=>$derivative]);
	}
}
