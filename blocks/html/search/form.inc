<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Apache_Solr_Response $this->solrObject
 */
use Application\Models\Department;
use Application\Models\Search;
use Blossom\Classes\Url;
use Blossom\Classes\View;
?>
<div>
	<h2>Search</h2>
	<form method="get" action="">
		<fieldset>
			<input name="search" />
			<button type="submit">
				<i class="fa fa-search"></i>
				Search
			</button>
		</fieldset>
		<fieldset>
			<?php
				$currentUrl = new Url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
				$facets = &$this->solrObject->facet_counts->facet_fields;

				foreach (Search::$searchableFields as $field=>$name) {
					$list = '';
					foreach ($facets->$field as $id=>$count) {
						if ($count) {
							$url = clone($currentUrl);
							$url->$field = $id;

							$value = View::escape(Search::getDisplayValue($field, $id));
							$list.= "<li><a href=\"$url\">$value ($count)</a></li>";
						}
					}
					if ($list) {
						echo "
						<div>
							<h3>$name</h3>
							<ul>$list</ul>
						</div>
						";
					}
				}
			?>
		</fieldset>
	</form>
</div>
