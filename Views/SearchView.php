<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class SearchView extends Template
{
    public function __construct(array $vars=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

		$this->blocks['panel-one'][] = new Block('search/form.inc', ['solrObject'=>$this->solrObject]);
		$this->blocks[] = new Block('search/parameters.inc',        ['solrObject'=>$this->solrObject]);
		$this->blocks[] = new Block('search/results.inc',           ['solrObject'=>$this->solrObject]);
    }
}
