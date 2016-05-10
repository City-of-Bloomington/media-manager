<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Departments;

use Application\Models\MediaTable;
use Application\Models\PeopleTable;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class InfoView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

		$department_id = $this->department->getId();

        $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $table = new MediaTable();
        $media = $table->find(['department_id'=>$department_id], null, 20, $page);

        $table = new PeopleTable();
        $people = $table->find(['department_id'=>$department_id]);

		$this->blocks[] = new Block('departments/view.inc', ['department' => $this->department]);
        $this->blocks[] = new Block('media/thumbnails.inc', ['media'      => $media]);
        $this->blocks[] = new Block('pageNavigation.inc',   ['paginator'  => $media]);
        $this->blocks[] = new Block('people/list.inc',      ['people'     => $people]);
    }
}
