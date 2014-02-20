<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Apache_Solr_Response $this->solrObject
 */
use Application\Models\Search;
use Blossom\Classes\Block;
use Blossom\Classes\SolrPaginatorAdapter;
use Zend\Paginator\Paginator;

$this->media = Search::hydrateDocs($this->solrObject);
include APPLICATION_HOME.'/blocks/html/media/thumbnails.inc';

// Solr rows start at 0, but pages start at 1
$currentPage = round($this->solrObject->response->start/Search::ITEMS_PER_PAGE) + 1;

$paginator = new Paginator(new SolrPaginatorAdapter($this->solrObject));

$paginator->setItemCountPerPage(Search::ITEMS_PER_PAGE);
$paginator->setCurrentPageNumber($currentPage);
$block = new Block('pageNavigation.inc',array('paginator'=>$paginator));
echo $block->render('html', $this->template);
