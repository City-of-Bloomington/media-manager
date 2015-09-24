<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
require_once __DIR__.'/../../configuration.inc';

use Application\Models\Media;
use Application\Models\Image;
use Blossom\Classes\Database;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function testIsValidSize()
    {
        $this->assertTrue (Image::isValidSize(Media::SIZE_THUMBNAIL));
        $this->assertTrue (Image::isValidSize(Media::SIZE_MEDIUM));
        $this->assertFalse(Image::isValidSize(null));
        $this->assertFalse(Image::isValidSize(''));
    }
}