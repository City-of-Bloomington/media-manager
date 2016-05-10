<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class MediaTable extends TableGateway
{
	public function __construct() { parent::__construct('media', __namespace__.'\Media'); }

	/**
	 * @param array $fields Key value pairs to select on
	 * @param array $order The default ordering to use for select
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @return array|Paginator
	 */
	public function find($fields=null, $order=['uploaded desc'], $itemsPerPage=null, $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['m.*'])
               ->from('media m');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					default:
                        $select->where("$key=?", $value);
				}
			}
		}
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
