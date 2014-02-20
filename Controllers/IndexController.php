<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Media;
use Application\Models\Search;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index()
	{
		$search = new Search();
		$solrObject = $search->query($_GET);

		$this->template->blocks['panel-one'][] = new Block('search/form.inc', ['solrObject'=>$solrObject]);
		$this->template->blocks[] = new Block('search/parameters.inc',        ['solrObject'=>$solrObject]);
		$this->template->blocks[] = new Block('search/results.inc',           ['solrObject'=>$solrObject]);
	}
}
