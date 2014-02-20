<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Apache_Solr_Response $this->solrObject
 */
use Application\Models\Search;
use Blossom\Classes\Url;
use Blossom\Classes\View;

if (!empty($_GET['query'])
	|| isset($this->solrObject->responseHeader->params->fq)) {

	$q = !empty($_GET['query'])
		? 'Results for: '.View::escape($_GET['query'])
		: 'Current Search';
	$fq = isset($this->solrObject->responseHeader->params->fq)
		? $this->solrObject->responseHeader->params->fq
		: array();

	echo "
	<div class=\"searchParameters\">
		<h2>$q</h2>
	";
	if (count($fq)) {
		$currentURL = new Url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

		// There may only be one filterQuery
		if (!is_array($fq)) { $fq = [$fq]; }

		foreach ($fq as $facet) {
			preg_match('/([^:]+):(.+)/', $facet, $matches);
			$key   = $matches[1];
			$value = $matches[2];

			$deleteUrl = clone($currentURL);
			if (isset($deleteUrl->$key)) { unset($deleteUrl->$key); }

			$displayName  = Search::$searchableFields[$key];
			$displayValue = Search::getDisplayValue($key, $value);

			$h = $this->template->getHelper('buttonLink');
			echo $h->buttonLink($deleteUrl, "$displayName: $displayValue", 'delete');
		}
	}
}
