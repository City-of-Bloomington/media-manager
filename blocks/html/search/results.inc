<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Apache_Solr_Response $this->solrObject
 */
use Application\Models\Search;
use Blossom\Classes\Block;
use Blossom\Classes\SolrPaginator;

$this->media = Search::hydrateDocs($this->solrObject);
$this->_include('media/thumbnails.inc');

$currentPage = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$paginator   = new SolrPaginator($this->solrObject, Search::ITEMS_PER_PAGE, $currentPage);
$block       = new Block('pageNavigation.inc', ['paginator'=>$paginator]);
echo $block->render('html', $this->template);
