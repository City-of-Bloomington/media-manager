<?php
/**
 * Class for working with a previously created search index.  Before this class
 * will work, you must have run /scripts/install_search.php
 *
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

use \Apache_Solr_Service;
use \Apache_Solr_Document;
use \Apache_Solr_Response;

class Search
{
	public $solrClient;

	const ITEMS_PER_PAGE  = 12;
	const DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

	/**
	 * Lookup table matching field codes to display names
	 */
	public static $searchableFields = [
		'department_id' => 'Department',
		'mime_type'     => 'Mime Type',
		'tag_id'        => 'Tag'
	];

	public function __construct()
	{
		$this->solrClient = new Apache_Solr_Service(
			SOLR_SERVER_HOSTNAME,
			SOLR_SERVER_PORT,
			SOLR_SERVER_PATH
		);
	}

	public function commit() { $this->solrClient->commit(); }

	/**
	 * Adds objects to the search index
	 *
	 * @param Object $entry
	 */
	public function add($entry)
	{
		$document = new Apache_Solr_Document();

		if ($entry instanceof Media) {
			$recordType = 'media';

			$document->addField('recordKey', "{$recordType}_{$entry->getId()}");
			$document->addField('recordType', $recordType);
			$document->addField("id",               $entry->getId());
			$document->addField('title',            $entry->getTitle());
			$document->addField('description',      $entry->getDescription());
			$document->addField('internalFilename', $entry->getInternalFilename());
			$document->addField('filename',         $entry->getFilename());
			$document->addField('mime_type',        $entry->getMime_type());
			$document->addField('media_type',       $entry->getMedia_type());
			$document->addField('md5',              $entry->getMd5());
			$document->addField('person_id',        $entry->getPerson_id());
			$document->addField('department_id',    $entry->getDepartment_id());
			$document->addField('uploaded', $entry->getUploaded(self::DATE_FORMAT), \DateTimeZone::UTC);

			foreach ($entry->getTags() as $tag) {
				$document->addField('tag_id', $tag->getId());
			}
		}
		else { throw new \Exception('search/unknownType'); }


		$this->solrClient->addDocument($document);
	}

	/**
	 * Removed an entry from the search index
	 *
	 * @param Object $entry
	 */
	public function remove($entry)
	{
		if ($entry instanceof Media) {
			$this->solrClient->deleteById('media_'.$entry->getId());
		}
		else { throw new \Exception('search/unknownType'); }
	}

	/**
	 * Alias of add
	 *
	 * Adding a document again to Solr will update the existing record
	 */
	public function update($entry) { $this->add($entry); }

	/**
	 * Alias for Search::remove
	 */
	public function delete($entry) { $this->remove($entry); }

	/**
	 * @param array $_GET
	 * @param string type One of the types known to the Search class (see Search->add())
	 * @return SolrObject
	 */
	public function query(&$get)
	{
		// Start with all the default query values
		$query = !empty($get['search'])
			? "{!df=combined}$get[search]"
			: '*:*';
		$additionalParameters = [];


		// Pagination
		$rows = self::ITEMS_PER_PAGE;
		$startingPage = 0;
		if (!empty($get['page'])) {
			$page = (int)$get['page'];
			if ($page < 1) { $page = 1; }

			// Solr rows start at 0, but pages start at 1
			$startingPage = ($page - 1) * $rows;
		}

		// Facets
		$additionalParameters['facet'] = 'true';
		$additionalParameters['facet.field'] = ['department_id','mime_type', 'tag_id'];

		// FQ
		$fq = [];
		if (!empty($get['department_id'])) { $fq[] = "department_id:$get[department_id]"; }
		if (!empty($get['mime_type']    )) { $fq[] = "mime_type:$get[mime_type]";         }
		if (!empty($get['tag_id']       )) { $fq[] = "tag_id:$get[tag_id]";               }
		if (count($fq)) { $additionalParameters['fq'] = $fq; }

		$solrResponse = $this->solrClient->search($query, $startingPage, $rows, $additionalParameters);
		return $solrResponse;
	}

	/**
	 * @param Apache_Solr_Response $object
	 * @return array An array of CRM models based on the search results
	 */
	public static function hydrateDocs(Apache_Solr_Response $o)
	{
		$models = array();
		if (isset($o->response->docs) && $o->response->docs) {
			foreach ($o->response->docs as $doc) {
				$class = __namespace__.'\\'.ucfirst($doc->recordType);
				$m = new $class($doc->id);
				$models[] = $m;
			}
		}
		else {
			header('HTTP/1.1 404 Not Found', true, 404);
		}
		return $models;
	}

	/**
	 * Retrieves full facet counts for a query
	 *
	 * Takes a solrResponse, drops the FQ and does a facet-only query
	 * Returns the facet results
	 *
	 * @param Apache_Solr_Response $solrResponse
	 * @return Apache_Solr_Response
	 */
	public function facetQuery(Apache_Solr_Response $solrResponse)
	{
		print_r($solrResponse);
	}

	/**
	 * Returns the display value of an object corresponding to a search field
	 *
	 * For each of the self::$searchableFields we need a way to look up the
	 * object corresponding to the value in the search index.
	 * Example: self::getDisplayName('department_id', 32);
	 *
	 * Returns null if the value is an invalid ID
	 *
	 * @param string $recordType
	 * @param string $fieldname
	 * @param string $value
	 * @return string
	 */
	public static function getDisplayValue($fieldname, $value)
	{
		if (isset(self::$searchableFields[$fieldname])) {
			if (false !== strpos($fieldname, '_id')) {
				try {
					$class = __namespace__.'\\'.ucfirst(substr($fieldname, 0, -3));
					$o = new $class($value);
					return $o->getName();
				}
				catch (Exception $e) {
					// Returns null if the $class ID is invalid
				}
			}
			else {
				return $value;
			}
		}
	}
}
