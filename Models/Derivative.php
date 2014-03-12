<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Derivative extends ActiveRecord
{
	protected $tablename = 'derivatives';

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
				$this->exchangeArray($id);
			}
			else {
				$zend_db = Database::getConnection();
				if (ActiveRecord::isId($id)) {
					$sql = 'select * from derivatives where id=?';
				}
				else {
					$sql = 'select * from derivatives where name=?';
				}
				$result = $zend_db->createStatement($sql)->execute([$id]);
				if (count($result)) {
					$this->exchangeArray($result->current());
				}
				else {
					throw new \Exception('derivatives/unknownDerivative');
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
		if (!$this->getName() || !$this->getSize()) {
			throw new \Exception('missingRequiredFields');
		}
	}

	public function save()   { parent::save();   }
	public function delete() { parent::delete(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()   { return parent::get('id');   }
	public function getName() { return parent::get('name'); }
	public function getSize() { return parent::get('size'); }
	public function getAspectRatio_width () { return parent::get('aspectRatio_width' ); }
	public function getAspectRatio_height() { return parent::get('aspectRatio_height'); }

	public function setName($s) { parent::set('name', $s); }
	public function setSize($s) { parent::set('size', $s); }
	public function setAspectRatio_width ($i) { parent::set('aspectRatio_width' , (int)$i); }
	public function setAspectRatio_height($i) { parent::set('aspectRatio_height', (int)$i); }

	public function handleUpdate($post)
	{
		$this->setName($post['name']);
		$this->setSize($post['size']);

		if (!empty($post['aspectRatio_width']) && !empty($post['aspectRatio_height'])) {
			$this->setAspectRatio_width ($post['aspectRatio_width' ]);
			$this->setAspectRatio_height($post['aspectRatio_height']);
		}
		else {
			$this->setAspectRatio_width ();
			$this->setAspectRatio_height();
		}
	}

	//----------------------------------------------------------------
	// Custom functions
	//----------------------------------------------------------------
	/**
	 * @return float
	 */
	public function getAspectRatio()
	{
		if ($this->getAspectRatio_width() && $this->getAspectRatio_height()) {
			return $this->getAspectRatio_width()/$this->getAspectRatio_height();
		}
	}
}
