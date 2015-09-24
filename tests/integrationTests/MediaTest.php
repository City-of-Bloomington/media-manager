<?php
/**
 * @copyright 2013-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
require_once __DIR__.'/../../configuration.inc';

use Application\Models\Media;
use Application\Models\Image;
use Blossom\Classes\Database;

class MediaTest extends PHPUnit_Framework_TestCase
{
	private $origFile = '';
	private $origFileWidth;
	private $origFileHeight;

	private $filesToCleanUp = [];

	public function __construct()
	{
		$this->origFile = __DIR__.'/Dan.png';

		$this->origFileWidth  = 1280;
		$this->origFileHeight = 1024;
	}

	public function setUp()
	{
		$this->filesToCleanUp = [];
	}

	public function tearDown()
	{
		foreach ($this->filesToCleanUp as $file) {
			if (file_exists($file)) {
				unlink($file);
			}
		}
	}

	public function testSaveDerivative()
	{
		$thumbnail = __DIR__.'/60/Dan.png';
		$this->filesToCleanUp[] = $thumbnail;

		Image::saveDerivative($this->origFile, 60);
		$this->assertTrue(file_exists($thumbnail), 'Thumbnail not generated');
	}

	public function testSaveCustomDerivative()
	{
        $thumbnail = __DIR__.'/test-thumbnail.png';
        copy($thumbnail, __DIR__.'/temp');

        $expectedDerivativeFile = __DIR__.'/100/Dan.png';

        $this->filesToCleanUp[] = $expectedDerivativeFile;

        Image::saveDerivative($this->origFile, 100, __DIR__.'/temp');
        $this->assertTrue(file_exists($expectedDerivativeFile), 'Custom derivative not saved');
	}

	/**
	 * Make sure the URL for the image is web accessible
	 */
	public function testGetURL()
	{
		$temp = __DIR__."/temp";

		$zend_db = Database::getConnection();
		$result = $zend_db->query("select * from media where media_type='image' limit 1")->execute();
		if (count($result)) {
			$row = $result->current();
			$media = new Media($row);

			$request = curl_init($media->getURL());
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
			file_put_contents($temp, curl_exec($request));
			$this->assertTrue(file_exists($temp), 'Media is not available over HTTP');

			$this->assertEquals($media->getFilesize(), filesize($temp), 'Filesizes do not match');

			$download = getimagesize($temp);
			$this->assertEquals($media->getWidth() , $download[0], 'Downloaded image size does not match original');
			$this->assertEquals($media->getHeight(), $download[1], 'Downloaded image size does not match original');
		}

		#if (file_exists($temp)) { unlink($temp); }
	}

	/**
	 * The thumbnails should be created automatically when first requested
	 *
	 * This is kind of like the Apache 404 trick, except we're sending
	 * all traffic to index.php, so it's handled there, instead of a 404
	 */
	public function testAutogenerateThumbnailsByURL()
	{
		$temp = __DIR__."/temp";

		$zend_db = Database::getConnection();
		// We have to choose a valid size, otherwise it will really 404
		$result = $zend_db->query('select min(size) as size from derivatives')->execute();
		if (count($result)) {
			$row  = $result->current();
			$size = $row['size'];
		}

		$result = $zend_db->query("select * from media where media_type='image' limit 1")->execute();
		if (isset($size) && count($result)) {
			$row = $result->current();
			$media = new Media($row);

			$media->deleteDerivatives();
			$this->assertFalse(file_exists($media->getFullPath($size)));

			$url = $media->getURL($size);
			$request = curl_init($media->getURL($size));
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
			file_put_contents($temp, curl_exec($request));
			$this->assertTrue(file_exists($temp), 'Thumbnail file not downloaded');

			$info = getimagesize($temp);

			$this->assertTrue(
				($info[0]==$size || $info[1]==$size),
				'Generated image is not the correct size'
			);

			#if (file_exists($temp)) { unlink($temp); }
		}
	}
}
