<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Department;
use Application\Models\DepartmentTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class DepartmentsController extends Controller
{
	private function loadDepartment($id)
	{
		try {
			return new Department($_REQUEST['department_id']);
		}
		catch (\Exception $e) {
			$_SESSION['errorMessages'][] = $e;
			header('Location: '.BASE_URL.'/departments');
			exit();
		}
	}

	public function index()
	{
		$table = new DepartmentTable();
		$list = $table->find();
		$this->template->blocks[] = new Block('departments/list.inc', ['departments'=>$list]);
	}

	public function view()
	{
		$department = $this->loadDepartment($_GET['department_id']);
		$this->template->blocks[] = new Block('departments/view.inc', ['department'=>$department]);

	}

	public function update()
	{
		if (!empty($_REQUEST['department_id'])) {
			$department = $this->loadDepartment($_REQUEST['department_id']);
		}
		else {
			$department = new Department();
		}

		if (isset($_POST['name'])) {
			try {
				$department->handleUpdate($_POST);
				$department->save();
				header('Location: '.BASE_URI.'/departments/view?department_id='.$department->getId());
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}

		$this->template->blocks[] = new Block('departments/updateForm.inc', ['department'=>$department]);
	}
}
