<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
require_once './configuration.inc';

use Application\Models\Image;
use Application\Models\Media;

class MediaTest extends PHPUnit_Framework_TestCase
{
	public function testGetDirectory()
	{
		$media = new Media();
		$this->assertEquals(date('Y/n/j'), $media->getDirectory());
	}

	public function testGetExtension()
	{
		$media = new Media();
		$this->assertEquals('png', $media->getExtension('Dan.png'));
	}

	public function testCreateValidFilename()
	{
		$media = new Media();
		$this->assertEquals('Dan.png', $media->createValidFilename('Dan.png'));
	}
}
