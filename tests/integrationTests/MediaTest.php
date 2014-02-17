<?php
/**
 * @copyright 2013-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
require_once __DIR__.'/../../configuration.inc';

use Application\Models\Image;
use Application\Models\Media;

class MediaTest extends PHPUnit_Framework_TestCase
{
	private $origFile = '';
	private $testFile = '';
	private $FILE = []; // stand-in for $_FILE
	private $testSize = 60;

	private $filesToCleanUp = [];

	public function __construct()
	{
		$this->origFile = __DIR__.'/Dan.png';
		$this->testFile = __DIR__.'/test.png';
		$this->FILE = array('tmp_name'=>$this->testFile,'name'=>'Dan.png');
	}

	/**
	 * Creates a fresh test image to use as a file upload
	 *
	 * Required, because the file upload is moved, not copied.
	 * In other words, the file gets deleted each time
	 */
	public function setUp()
	{
		copy($this->origFile, $this->testFile);
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

	public function testSetFile()
	{
		$media = new Media();
		$media->setFile($this->FILE);
		$this->assertEquals('Dan.png', $media->getFilename());

		$newFile = DATA_HOME."/data/media/{$media->getDirectory()}/{$media->getInternalFilename()}";
		$this->filesToCleanUp[] = $newFile;
		$this->assertTrue(file_exists($newFile));
	}

	public function testInternalFilename()
	{
		$media = new Media();
		$media->setFile($this->FILE);

		$this->assertNotEmpty($media->getInternalFilename());

		$newFile = DATA_HOME."/data/media/{$media->getDirectory()}/{$media->getInternalFilename()}";
		$this->filesToCleanUp[] = $newFile;
	}

	public function testGenerateAndClearThumbnail()
	{
		$media = new Media();
		$media->setFile($this->FILE);

		$image = new Image($media);

		ob_start();
		$image->output($this->testSize);
		ob_end_clean();

		$newFile   = DATA_HOME."/data/media/{$media->getDirectory()}/{$media->getInternalFilename()}";
		$thumbnail = DATA_HOME."/data/media/{$media->getDirectory()}/{$this->testSize}/{$media->getInternalFilename()}";
		$this->assertTrue(file_exists($thumbnail));

		$this->filesToCleanUp[] = $newFile;
		$this->filesToCleanUp[] = $thumbnail;

		$info = getimagesize($thumbnail);
		$this->assertTrue(($info[0]==$this->testSize || $info[1]==$this->testSize));

		$media->deleteDerivatives();
		$this->assertFalse(file_exists($thumbnail));
	}

	/**
	 * Make sure the URL for the image is web accessible
	 */
	public function testGetURL()
	{
		$media = new Media();
		$media->setFile($this->FILE);

		$newFile   = DATA_HOME."/data/media/{$media->getDirectory()}/{$media->getInternalFilename()}";
		$this->filesToCleanUp[] = $newFile;

		$temp = __DIR__."/temp.png";
		$this->filesToCleanUp[] = $temp;

		$request = curl_init($media->getUrl());
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
		file_put_contents($temp, curl_exec($request));
		$this->assertTrue(file_exists($temp), 'Media is not available over HTTP');

		$origInfo = getimagesize($this->origFile);
		$download = getimagesize($temp);

		$this->assertEquals($origInfo[0], $download[0], 'Downloaded image size does not match original');
		$this->assertEquals($origInfo[1], $download[1], 'Downloaded image size does not match original');
	}

	/**
	 * The thumbnails should be created automatically when first requested
	 *
	 * This is kind of like the Apache 404 trick, except we're sending
	 * all traffic to index.php, so it's handled there, instead of a 404
	 */
	public function testAutogenerateThumbnailsByURL()
	{
		$media = new Media();
		$media->setFile($this->FILE);
		$media->setPerson_id(1);
		$media->save();

		$newFile   = DATA_HOME."/data/media/{$media->getDirectory()}/{$media->getInternalFilename()}";
		$thumbnail = DATA_HOME."/data/media/{$media->getDirectory()}/{$this->testSize}/{$media->getInternalFilename()}";
		$temp = __DIR__."/temp.png";
		#$this->filesToCleanUp[] = $newFile;
		#$this->filesToCleanUp[] = $thumbnail;
		#$this->filesToCleanUp[] = $temp;

		echo "Downloading {$media->getUrl($this->testSize)}\n";

		$request = curl_init($media->getUrl($this->testSize));
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
		file_put_contents($temp, curl_exec($request));
		$this->assertTrue(file_exists($thumbnail), 'Thumbnail file does not exist');
		$this->assertTrue(file_exists($temp), 'Thumbnail file not downloaded');

		$info = getimagesize($temp);
		echo "Generated thumbnail\n";
		print_r($info);
		$this->assertTrue(
			($info[0]==$this->testSize || $info[1]==$this->testSize),
			'Generated image is not the correct size'
		);

		$media->delete();
	}
}
