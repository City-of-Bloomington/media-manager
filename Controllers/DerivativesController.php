<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Derivative;
use Application\Models\DerivativesTable;
use Blossom\Classes\Controller;

class DerivativesController extends Controller
{
	public function index()
	{
		$table = new DerivativesTable();
		$derivatives = $table->find();

		return new \Application\Views\Derivatives\ListView(['derivatives'=>$derivatives]);
	}

	public function update()
	{
        $return_url = self::generateUrl('derivatives.index');

		if (!empty($_REQUEST['id'])) {
			try {
				$derivative = new Derivative($_REQUEST['id']);
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
				header('Location: '.$return_url);
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
				header('Location: '.$return_url);
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		return new \Application\Views\Derivatives\UpdateView(['derivative'=>$derivative]);
	}
}
