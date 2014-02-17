<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Blossom\Classes\Controller;

class UploadsController extends Controller
{
	public function index()
	{
		$this->template->blocks[] = new Block('uploads/form.inc');
		$this->template->blocks[] = new Block('uploads/files.inc', ['user'=>$_SESSION['USER']]);
	}
}
