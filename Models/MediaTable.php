<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;
use Zend\Db\Sql\Select;

class MediaTable extends TableGateway
{
	public function __construct() { parent::__construct('media', __namespace__.'\Media'); }

	public function find($fields=null, $order=['uploaded desc'], $paginated=false, $limit=null)
	{
		$select = new Select('media');
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
		return parent::performSelect($select, $order, $paginated, $limit);
	}
}
