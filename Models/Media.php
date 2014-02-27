<?php
/**
 * Files will be stored as /data/media/YYYY/MM/DD/$media_id.ext
 * User provided filenames will be stored in the database
 *
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Media extends ActiveRecord
{
	// These are the absolute minimum of sizes needed to be declared
	// The rest of the sizes will be added to the database by users
	const SIZE_THUMBNAIL = 80;
	const SIZE_MEDIUM    = 350;
	
	const REGEX_FILENAME_EXT = '/(^.*)\.([^\.]+)$/';

	protected $tablename = 'media';

	protected $person;
	protected $department;

	private $tags = [];

	/**
	 * Whitelist of accepted file types
	 */
	public static $extensions = [
		'jpg' =>['mime_type'=>'image/jpeg','media_type'=>'image'],
		'gif' =>['mime_type'=>'image/gif', 'media_type'=>'image'],
		'png' =>['mime_type'=>'image/png', 'media_type'=>'image'],
		'tiff'=>['mime_type'=>'image/tiff','media_type'=>'image']
		#'pdf' =>array('mime_type'=>'application/pdf','media_type'=>'attachment'),
		#'rtf' =>array('mime_type'=>'application/rtf','media_type'=>'attachment'),
		#'doc' =>array('mime_type'=>'application/msword','media_type'=>'attachment'),
		#'xls' =>array('mime_type'=>'application/msexcel','media_type'=>'attachment'),
		#'gz'  =>array('mime_type'=>'application/x-gzip','media_type'=>'attachment'),
		#'zip' =>array('mime_type'=>'application/zip','media_type'=>'attachment'),
		#'txt' =>array('mime_type'=>'text/plain','media_type'=>'attachment'),
		#'wmv' =>array('mime_type'=>'video/x-ms-wmv','media_type'=>'video'),
		#'mov' =>array('mime_type'=>'video/quicktime','media_type'=>'video'),
		#'rm'  =>array('mime_type'=>'application/vnd.rn-realmedia','media_type'=>'video'),
		#'ram' =>array('mime_type'=>'audio/vnd.rn-realaudio','media_type'=>'audio'),
		#'mp3' =>array('mime_type'=>'audio/mpeg','media_type'=>'audio'),
		#'mp4' =>array('mime_type'=>'video/mp4','media_type'=>'video'),
		#'flv' =>array('mime_type'=>'video/x-flv','media_type'=>'video'),
		#'wma' =>array('mime_type'=>'audio/x-ms-wma','media_type'=>'audio'),
		#'kml' =>array('mime_type'=>'application/vnd.google-earth.kml+xml','media_type'=>'attachment'),
		#'swf' =>array('mime_type'=>'application/x-shockwave-flash','media_type'=>'attachment'),
		#'eps' =>array('mime_type'=>'application/postscript','media_type'=>'attachment')
	];

	/**
	 * Populates the object with data
	 *
	 * Passing in an associative array of data will populate this object without
	 * hitting the database.
	 *
	 * Passing in a scalar will load the data from the database.
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int|array $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$this->exchangeArray($id);
			}
			else {
				$zend_db = Database::getConnection();
				if (ActiveRecord::isId($id)) {
					$sql = 'select * from media where id=?';
				}
				else {
					// Media internalFilenames include the original file extensions
					// However, the filename being requested may be for a generated thumbnail
					// We need to chop off the extension and do a wildcard search
					$filename = preg_replace('/[^.]+$/','',$id);
					$id = "$filename%";
					$sql = 'select * from media where internalFilename like ?';
				}
				$result = $zend_db->createStatement($sql)->execute([$id]);
				if (count($result)) {
					$this->exchangeArray($result->current());
				}
				else {
					throw new \Exception('media/unknownMedia');
				}
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->setUploaded('now');

			if (isset($_SESSION['USER'])) {
				$this->setPerson($_SESSION['USER']);
			}
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->data['filename'])   { throw new \Exception('media/missingFilename');  }
		if (!$this->data['mime_type'])  { throw new \Exception('media/missingMimeType');  }
		if (!$this->data['media_type']) { throw new \Exception('media/missingMediaType'); }

		if (!$this->getPerson_id()) { $this->setPerson($_SESSION['USER']); }
		if (!$this->getDepartment_id()) {
			$this->setDepartment_id($this->getPerson()->getDepartment_id());
		}
	}

	public function save()
	{
		parent::save();
		$this->saveTags();

		$search = new Search();
		$search->add($this);
		$search->commit();
	}

	private function saveTags()
	{
		if ($this->getId()) {
			$zend_db = Database::getConnection();

			$zend_db->query('delete from media_tags where media_id=?', [$this->getId()]);

			$query = $zend_db->createStatement('insert media_tags set media_id=?,tag_id=?');
			foreach($this->getTags() as $tag) {
				$query->execute([$this->getId(), $tag->getId()]);
			}
		}
	}

	/**
	 * Deletes the file from the hard drive
	 */
	public function delete()
	{
		if ($this->getId()) {
			$zend_db = Database::getConnection();
			$zend_db->query('delete from media_tags where media_id=?')->execute([$this->getId()]);

			$this->deleteDerivatives();

			unlink(DATA_HOME."/data/media/{$this->getDirectory()}/{$this->getInternalFilename()}");
			parent::delete();

			$search = new Search();
			$search->remove($this);
			$search->commit();
		}
	}

	/**
	 * Delete any cached preview version of this media
	 */
	public function deleteDerivatives()
	{
		$uniqid = preg_replace('/[^.]+$/', '', $this->getInternalFilename());
		$pattern = DATA_HOME."/data/media/{$this->getDirectory()}/*/$uniqid*";

		foreach(glob($pattern) as $file) { unlink($file); }
	}
	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()          { return parent::get('id');          }
	public function getFilename()    { return parent::get('filename');    }
	public function getMime_type()   { return parent::get('mime_type');   }
	public function getMedia_type()  { return parent::get('media_type');  }
	public function getMd5()         { return parent::get('md5');         }
	public function getTitle()       { return parent::get('title');       }
	public function getDescription() { return parent::get('description'); }
	public function getPerson_id()     { return parent::get('person_id');   }
	public function getDepartment_id() { return parent::get('department_id'); }
	public function getPerson()        { return parent::getForeignKeyObject(__namespace__.'\Person', 'person_id'); }
	public function getDepartment()    { return parent::getForeignKeyObject(__namespace__.'\Department', 'department_id'); }
	public function getUploaded($f=null, DateTimeZone $tz=null) { return parent::getDateData('uploaded', $f, $tz); }

	public function setMd5        ($s) { parent::set('md5',         $s); }
	public function setTitle      ($s) { parent::set('title',       $s); }
	public function setDescription($s) { parent::set('description', $s); }
	public function setPerson_id    ($i) { parent::setForeignKeyField (__namespace__.'\Person', 'person_id', $i); }
	public function setDepartment_id($i) { parent::setForeignKeyField (__namespace__.'\Department', 'department_id', $i); }
	public function setPerson       ($o) { parent::setForeignKeyObject(__namespace__.'\Person', 'person_id', $o);  }
	public function setDepartment   ($o) { parent::setForeignKeyObject(__namespace__.'\Department', 'department_id', $o); }
	public function setUploaded   ($d) { parent::setDateData('uploaded', $d); }


	public function getType() { return $this->getMedia_type(); }
	public function getModified($f=null, DateTimeZone $tz=null) { return $this->getUploaded($f, $tz); }

	/**
	 * @param array $post
	 */
	public function handleUpdate($post)
	{
		$fields = ['title', 'description', 'tags', 'department_id'];
		foreach ($fields as $f) {
			if (isset($post[$f])) {
				$set = 'set'.ucfirst($f);
				$this->$set($post[$f]);
			}
		}
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	/**
	 * Populates this object by reading information on a file
	 *
	 * This function does the bulk of the work for setting all the required information.
	 * It tries to read as much meta-data about the file as possible
	 *
	 * @param array|string Either a $_FILES array or a path to a file
	 */
	public function setFile($file)
	{
		// Handle passing in either a $_FILES array or just a path to a file
		$tempFile = is_array($file) ? $file['tmp_name'] : $file;
		$filename = is_array($file) ? basename($file['name']) : basename($file);
		if (!$tempFile) {
			throw new \Exception('media/uploadFailed');
		}

		// Make sure the file's not already in the system
		$this->setMd5(md5_file($tempFile));
		$table = new MediaTable();
		$list = $table->find(['md5'=>$this->getMd5()]);
		if (count($list)) {
			# If we're updating a file, we expect to get one
			# hit back - but it needs to have the current media_id
			# Otherwise, then the file is already in the system
			if ($list->current()->getId() != $this->getId()) {
				throw new \Exception('media/fileAlreadyExists');
			}
		}

		// Clean all bad characters from the filename
		$filename = $this->createValidFilename($filename);
		$this->data['filename'] = $filename;
		$extension = self::getExtension($filename);

		// Find out the mime type for this file
		if (array_key_exists(strtolower($extension),Media::$extensions)) {
			$this->data['mime_type']  = Media::$extensions[$extension]['mime_type'];
			$this->data['media_type'] = Media::$extensions[$extension]['media_type'];
		}
		else {
			throw new \Exception('media/unknownFileType');
		}


		// Move the file where it's supposed to go
		$directory = $this->getDirectory();
		if (!is_dir(DATA_HOME."/data/media/$directory")) {
			mkdir  (DATA_HOME."/data/media/$directory",0777,true);
		}
		$newFile  = DATA_HOME."/data/media/$directory/{$this->getInternalFilename()}";
		rename($tempFile, $newFile);
		chmod($newFile, 0666);

		// Check and make sure the file was saved
		if (!is_file($newFile)) {
			throw new \Exception('media/badServerPermissions');
		}

		$this->deleteDerivatives();
	}

	/**
	 * Returns the path of the file, relative to /data/media
	 *
	 * Media is stored in the data directory, outside of the web directory
	 * This variable only contains the partial path.
	 * This partial path can be concat with APPLICATION_HOME or BASE_URL
	 *
	 * @return string
	 */
	public function getDirectory()
	{
		return $this->getUploaded('Y/n/j');
	}

	/**
	 * Returns the file name used on the server
	 *
	 * We do not use the filename the user chose when saving the files.
	 * We generate a unique filename the first time the filename is needed.
	 * This filename will be saved in the database whenever this media is
	 * finally saved.
	 *
	 * @return string
	 */
	public function getInternalFilename()
	{
		$filename = parent::get('internalFilename');
		if (!$filename) {
			$filename = uniqid().'.'.self::getExtension($this->getFilename());
			parent::set('internalFilename', $filename);
		}
		return $filename;
	}

	/**
	 * @return string
	 */
	public static function getExtension($filename)
	{
		if (preg_match("/[^.]+$/", $filename, $matches)) {
			return strtolower($matches[0]);
		}
		else {
			echo "$filename has no extension\n";
			throw new \Exception('media/missingExtension');
		}
	}

	/**
	 * Returns the URL to this media
	 *
	 * @param int $size
	 * @return string
	 */
	public function getUrl($size=null) { return BASE_URL.$this->getMediaPath($size); }
	public function getUri($size=null) { return BASE_URI.$this->getMediaPath($size); }
	private function getMediaPath($size=null)
	{
		$url = "/m/{$this->getDirectory()}";
		if (!empty($size)) {
			$size = (int)$size;
			$url.= "/$size";
		}
		$url.= "/{$this->getInternalFilename()}";
		if ($size) {
			// All derivatives should be PNG
			$url = preg_replace('/[^.]+$/', 'png', $url);
		}
		return $url;
	}

	/**
	 * @return int
	 */
	public function getFilesize()
	{
		return filesize(DATA_HOME."/data/media/{$this->getDirectory()}/{$this->getInternalFilename()}");
	}

	/**
	 * Cleans a filename of any characters that might cause problems on filesystems
	 *
	 * @return string
	 */
	public static function createValidFilename($string)
	{
		// No bad characters
		$string = preg_replace('/[^A-Za-z0-9_\.\s]/','',$string);

		// Convert spaces to underscores
		$string = preg_replace('/\s+/','_',$string);

		// Lower case any file extension
		if (preg_match(self::REGEX_FILENAME_EXT,$string,$matches)) {
			$string = $matches[1].'.'.strtolower($matches[2]);
		}

		return $string;
	}

	/**
	 * @return boolean
	 */
	public static function isValidFiletype($filename)
	{
		$ext = self::getExtension($filename);
		return array_key_exists(strtolower($ext),self::$extensions);
	}

	/**
	 * Returns an array of tags with the tag_id as the key
	 *
	 * @return array
	 */
	public function getTags()
	{
		if (!count($this->tags)) {
			$table = new TagTable();
			$list = $table->find(['media_id'=>$this->getId()]);

			foreach($list as $tag) {
				$this->tags[$tag->getId()] = $tag;
			}
		}
		return $this->tags;
	}

	/**
	 * Takes a string of comma-separated tags
	 *
	 * @param string $string
	 */
	public function setTags($string)
	{
		foreach(Tag::tokenize($string) as $name) {
			try {
				$tag = new Tag($name);
			}
			catch (\Exception $e) {
				$tag = new Tag();
				$tag->setName($name);
				$tag->save();
			}

			$this->tags[$tag->getId()] = $tag;
		}
	}
}
