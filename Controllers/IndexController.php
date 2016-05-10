<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Search;
use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index()
	{
		$search = new Search();
		$solrObject = $search->query($_GET);

		return new \Application\Views\SearchView(['solrObject'=>$solrObject]);
	}
}
