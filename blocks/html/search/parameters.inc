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

if (!empty($_GET['search'])
	|| isset($this->solrObject->responseHeader->params->fq)) {

	$q = !empty($_GET['search'])
		? 'Results for: '.View::escape($_GET['search'])
		: 'Current Search';
	$fq = isset($this->solrObject->responseHeader->params->fq)
		? $this->solrObject->responseHeader->params->fq
		: array();

	echo "
	<div class=\"searchParameters\">
		<h2>$q</h2>
	";

	$currentURL = new Url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	$buttonHelper = $this->template->getHelper('buttonLink');

	if (!empty($_GET['search'])) {
		$deleteUrl = clone($currentURL);
		if (isset($deleteUrl->search)) { unset($deleteUrl->search); }

		$displayName  = 'Search';
		$displayValue = View::escape($_GET['search']);
		echo $buttonHelper->buttonLink($deleteUrl, "$displayName:$displayValue", 'delete');
	}

	if (count($fq)) {
		// There may only be one filterQuery
		if (!is_array($fq)) { $fq = [$fq]; }

		foreach ($fq as $facet) {
			preg_match('/([^:]+):(.+)/', $facet, $matches);
			$key   = $matches[1];
			$value = $matches[2];

			$deleteUrl = clone($currentURL);
			switch ($key) {
				case 'aspectRatio':
					if (isset($deleteUrl->aspectRatio_width )) { unset($deleteUrl->aspectRatio_width ); }
					if (isset($deleteUrl->aspectRatio_height)) { unset($deleteUrl->aspectRatio_height); }

					$displayName  = 'Aspect Ratio';
					$displayValue = View::escape("$_GET[aspectRatio_width]x$_GET[aspectRatio_height]");
				break;

				default:
					if (isset($deleteUrl->$key)) { unset($deleteUrl->$key); }

					$displayName  = Search::$facetFields[$key];
					$displayValue = Search::getDisplayValue($key, $value);
			}

			echo $buttonHelper->buttonLink($deleteUrl, "$displayName: $displayValue", 'delete');
		}
	}
}
