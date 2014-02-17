<?php
/**
 * Class for working with users' upload directories
 *
 * Users get a directory created for them inside the uploads directory.
 * Files that a user uploads will get added to that directory.
 * Uploaded files remain in the user's upload directory until imported
 * into the database
 *
 * @copyright 2008-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class UploadDirectory Implements \Countable, \IteratorAggregate
{
	private $user;
	private $data = [];

	public function __construct(Person $user)
	{
		$this->user = $user;
		$dir = $this->getDirectory();
		if (!is_dir($dir)) { mkdir($dir,0777); }
	}

	/**
	 * Returns the full path to a user's upload directory
	 *
	 * @return string
	 */
	public function getDirectory()
	{
		return DATA_HOME.'/data/uploads/'.$this->user->getUsername();
	}

	/**
	 * Add a file to the upload directory
	 *
	 * Can handle either a single entry from $_FILES or
	 * a full path to a file
	 *
	 * @param string|array $file
	 */
	public function add($file)
	{
		if (is_array($file)) {
			$filename = Media::createValidFilename($file['name']);
			if (Media::isValidFiletype($filename)) {
				if (!move_uploaded_file($file['tmp_name'],"{$this->getDirectory()}/$filename")) {
					throw new \Exception('uploads/failedAddingFile');
				}
			}
			else {
				throw new \Exception('uploads/invalidFiletype');
			}
		}
		elseif(is_file($file)) {
			$filename = Media::createValidFilename(basename($file));
			if (Media::isValidFiletype($filename)) {
				rename($file,"{$this->getDirectory()}/$filename");
			}
			else {
				throw new \Exception('uploads/invalidFiletype');
			}
		}
		else {
			throw new \Exception('uploads/unknownFile');
		}
	}

	/**
	 * Loads all the new photos into the database
	 */
	public function import()
	{
		foreach(glob($this->getDirectory().'/*.*') as $file) {
			$media = new Media();
			$media->setPerson($this->user);
			$media->setFile($file);
			$media->save();
		}

		foreach(glob($this->getDirectory().'/thumbnail/*.*') as $thumbnail) {
			unlink($thumbnail);
		}
	}


	/**
	 * @implements Countable
	 */
	public function count() { return count(glob($this->getDirectory().'/*.*')); }

	/**
	 * @implements IteratorAggregate
	 */
	public function getIterator() { return new \DirectoryIterator($this->getDirectory()); }
}
