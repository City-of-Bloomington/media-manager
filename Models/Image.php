<?php
/**
 * A Decorator class for generic Media.
 *
 * This adds functions to generic Media that deal with Images
 * Image data is stored in a seperate table, so it still extends ActiveRecord
 *
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Image
{
    private $media;

	/**
	 * Loads data from the images table
	 *
	 * @param Media $media
	 */
	public function __construct(Media $media)
	{
        $this->media = $media;
	}

	/**
	 * Allows us to call media methods from this class
	 */
	public function __call($method, $args)
	{
		if (is_callable($this->media, $method)) {
			return call_user_func_array([$this->media, $method], $args);
		}

		throw new \BadMethodCallException();
	}


	/**
	 * Use ImageMagick to create a thumbnail file for the given image
	 *
	 * Input must be a full path.
	 * The resized image file will be saved in $inputPath/$size/$inputFilename.$ext
	 * The sizes array determines the output filetype (gif,jpg,png)
	 * ie. /var/www/sites/photobase/uploads/username/something.jpg
	 *
	 * @param string $inputFile Full path to an image file
	 * @param string $size The desired bounding box size
	 */
	public static function saveDerivative($inputFile, $size)
	{
		$size = (int)$size;
		$directory = dirname($inputFile)."/$size";

		preg_match(Media::REGEX_FILENAME_EXT, basename($inputFile), $matches);
		$filename = $matches[1];

		if (!is_dir($directory)) {
			if (!mkdir($directory, 0777, true)) {
				throw new \Exception('media/PermissionDenied');
			}
		}

		$dimensions = $size.'x'.$size;
		$newFile = "$directory/$filename.png";

		exec(IMAGEMAGICK."/convert $inputFile -channel rgba -alpha set -resize '$dimensions>' $newFile");
	}

	/**
	 * Streams a derivative file to the browser
	 *
	 * @param int $size
	 */
	public function outputDerivative($size)
	{
		$size = (int)$size;

		$directory = SITE_HOME."/media/{$this->media->getDirectory()}";
		$filename = $this->media->getInternalFilename();

		$thumbnailDirectory = "$directory/$size";

		preg_match(Media::REGEX_FILENAME_EXT, $filename, $matches);
		$resizedFile = $matches[1].'.png';

		if (!is_file("$thumbnailDirectory/$resizedFile")) {
			self::saveDerivative("$directory/$filename", $size);
		}

		readfile("$thumbnailDirectory/$resizedFile");
	}

	/**
	 * Checks if the provided size is a known derivative size
	 *
	 * @param int $size
	 * @return boolean
	 */
	public static function isValidSize($size)
	{
		if ($size == Media::SIZE_THUMBNAIL || $size == Media::SIZE_MEDIUM) {
			return true;
		}

		$table = new DerivativesTable();
		$sizes = $table->find(['size'=>$size]);
		return count($sizes) ? true : false;
	}

	/**
	 * @return float
	 */
	public function getAspectRatio()
	{
        if ($this->getWidth() && $this->getHeight()) {
            return $this->getWidth()/$this->getHeight();
        }
	}

	/**
	 * Returns the width of the original image
	 *
	 * @return int
	 */
	public function getWidth()
	{
        static $width;
        if   (!$width) { $width = $this->getImageWidth(); }
        return $width;
	}
	/**
	 * Returns the width of the requested version of an image
	 *
	 * @param string $size The version of the image (see self::$sizes)
	 * @return int
	 */
	public function getImageWidth($size=null)
	{
		return exec(IMAGEMAGICK."/identify -format '%w' ".$this->media->getFullPath($size));
	}

	/**
	 * Returns the height of the original image
	 *
	 * @return int
	 */
	public function getHeight()
	{
        static $height;
        if   (!$height) { $height = $this->getImageHeight(); }
        return $height;
    }

	/**
	 * Returns the height of the requested version of an image
	 *
	 * @param string $size The version of the image (see self::$sizes)
	 * @return int
	 */
	public function getImageHeight($size=null)
	{
		return exec(IMAGEMAGICK."/identify -format '%h' ".$this->media->getFullPath($size));
	}
}
