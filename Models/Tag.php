<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Tag extends ActiveRecord
{
	protected $tablename = 'tags';

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
	 * @param int|string|array $id (ID, email, username)
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$this->data = $id;
			}
			else {
				if (ActiveRecord::isId($id)) {
					$sql = 'select * from tags where id=?';
				}
				else {
					$sql = 'select * from tags where name=?';
				}
				$rows = parent::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
				else {
					throw new \Exception('tags/unknownTag');
				}
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	public function validate()
	{
		if (!$this->getName()) { throw new \Exception('missingName'); }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()   { return parent::get('id');   }
	public function getName() { return parent::get('name'); }

	public function setName($s) { parent::set('name', strtolower($s)); }

	public function handleUpdate($post)
	{
		$this->setName($post['name']);
	}

	//----------------------------------------------------------------
	// Custom functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getName(); }

	public function getMedia()
	{
		$table = new MediaTable();
		return $table->find(['tag_id'=>$this->getId()]);
	}

	/**
	 * @param string $string
	 * @return array
	 */
	public static function tokenize($string)
	{
		$tags = [];
		foreach (explode(',', $string) as $t) {
			$t = strtolower(trim($t));
			$t = preg_replace('/\s+/', ' ', $t);
			if ($t) {
				$tags[] = $t;
			}
		}
		return $tags;
	}
}
