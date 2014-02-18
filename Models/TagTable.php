<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;
use Zend\Db\Sql\Select;

class TagTable extends TableGateway
{
	public function __construct() { parent::__construct('tags', __namespace__.'\Tag'); }

	public function find($fields=null, $order='name', $paginated=false, $limit=null)
	{
		$select = new Select('tags');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'media_id':
						$select->join(['m'=>'media_tags'], 'tags.id=m.tag_id', []);
						$select->where(['m.media_id'=>$value]);
						break;

					default:
						$select->where([$key=>$value]);
				}
			}
		}
		return parent::performSelect($select, $order, $paginated, $limit);
	}
}
