<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Department;
use Application\Models\DepartmentTable;
use Application\Models\MediaTable;
use Application\Models\PeopleTable;
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

		$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$table = new MediaTable();
		$media = $table->find(['department_id'=>$department->getId()], null, true);
		$media->setCurrentPageNumber($page);
		$media->setItemCountPerPage(20);
		$this->template->blocks[] = new Block('media/thumbnails.inc', ['media'=>$media]);
		$this->template->blocks[] = new Block('pageNavigation.inc', ['paginator'=>$media]);



		$table = new PeopleTable();
		$people = $table->find(['department_id'=>$department->getId()], null, true);
		$this->template->blocks[] = new Block('people/list.inc', ['people'=>$people]);

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
