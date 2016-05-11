<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
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
		try { return new Department($id); }
		catch (\Exception $e) {
			$_SESSION['errorMessages'][] = $e;
			$url = self::generateUrl('departments.index');
			header('Location: '.$url);
			exit();
		}
	}

	public function index(array $params)
	{
		$table = new DepartmentTable();
		$list = $table->find();

		return new \Application\Views\Departments\ListView(['departments'=>$list]);
	}

	public function view(array $params)
	{
        if (!empty($_GET['id'])) {
            $department = $this->loadDepartment($_GET['id']);
            return new \Application\Views\Departments\InfoView(['department'=>$department]);
        }
        else {
            return \Application\Views\NotFoundView();
        }

	}

	public function update(array $params)
	{
        $department = !empty($_REQUEST['id'])
			? $this->loadDepartment($_REQUEST['id'])
			: new Department();

		if (isset($_POST['name'])) {
			try {
				$department->handleUpdate($_POST);
				$department->save();
				$url = self::generateUrl('departments.view', ['id'=>$department->getId()]);
				header('Location: '.$url);
				exit();
			}
			catch (\Exception $e) {
				$_SESSION['errorMessages'][] = $e;
			}
		}
		return new \Application\Views\Departments\UpdateView(['department'=>$department]);
	}
}
