<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Media;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class RenderView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('media', $format, $vars);

		$this->blocks[] = new Block('media/download.inc', [
            'media'=>$this->media, 'derivative'=>$this->derivative
        ]);
    }
}
