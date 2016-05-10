<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class TagTable extends TableGateway
{
	public function __construct() { parent::__construct('tags', __namespace__.'\Tag'); }

	public function find($fields=null, $order=['t.name'], $paginated=false, $limit=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['t.*'])
               ->from('tags t');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'media_id':
                        $select->join('left', 'media_tags m', 't.id=m.tag_id');
                        $select->where('m.media_id=?', $value);
						break;

					default:
                        $select->where("t.$key=?", $value);
				}
			}
		}
		return parent::performSelect($select, $order, $paginated, $limit);
	}
}
