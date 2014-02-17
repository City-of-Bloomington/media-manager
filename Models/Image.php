<?php
/**
 * @copyright 2014 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class Image extends Media
{
	public function __construct(Media $media)
	{
		$this->data = $media->data;

		if ($this->getMedia_type() != 'image') {
			throw new \Exception('media/nonimage');
		}
	}

	/**
	 * Generates, caches, and outputs smaller versions of images
	 *
	 * @param int $size Bounding box in pixels
	 */
	public function output($size)
	{
		// If they don't specify size, just output the opriginal file
		$directory    = DATA_HOME."/data/media/{$this->getDirectory()}";
		$original = $this->getInternalFilename();
		if (!$size) {
			readfile("$directory/$original");
		}
		else {
			$size = (int)$size;
			$thumbnailDirectory = "$directory/$size";

			preg_match(Media::REGEX_FILENAME_EXT, $original, $matches);
			$resizedFile = $matches[1].'.png';

			if (!is_file("$thumbnailDirectory/$resizedFile")) {
				self::saveDerivative("$directory/$original", $size);
			}

			readfile("$thumbnailDirectory/$resizedFile");
		}
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
	 * @param int $size The desired bounding box size
	 */
	public static function saveDerivative($inputFile, $size)
	{
		$size = (int)$size;
		$directory = dirname($inputFile)."/$size";

		preg_match(Media::REGEX_FILENAME_EXT, basename($inputFile), $matches);
		$filename = $matches[1];

		if (!is_dir($directory)) { mkdir($directory, 0777, true); }

		$dimensions = $size.'x'.$size;
		$newFile = "$directory/$filename.png";
		exec(IMAGEMAGICK."/convert $inputFile -resize '$dimensions' $newFile");
	}

	private function getFullPathForSize($size=null)
	{
		$size = (int)$size;

		preg_match(Media::REGEX_FILENAME_EXT, $this->getInternalFilename(), $matches);
		$filename = $matches[1];

		if ($size) {
			return "{$this->getDirectory()}/$size/$filename.png";
		}
		else {
			// Return the size of the original
			return "{$this->getDirectory()}/{$this->getInternalFilename()}";
		}
	}

	/**
	 * Returns the width of the requested version of an image
	 *
	 * @param string $size The version of the image (see self::$sizes)
	 */
	public function getWidth($size=null)
	{
		return exec(IMAGEMAGICK."/identify -format '%w' ".$this->getFullPathForSize($size));
	}

	/**
	 * Returns the height of the requested version of an image
	 *
	 * @param string $size The version of the image (see self::$sizes)
	 */
	public function getHeight($size=null)
	{
		return exec(IMAGEMAGICK."/identify -format '%h' ".$this->getFullPathForSize($size));
	}
}