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

$search = !empty($_GET['search'])             ? View::escape($_GET['search'])             : '';
$width  = !empty($_GET['aspectRatio_width' ]) ? View::escape($_GET['aspectRatio_width' ]) : '';
$height = !empty($_GET['aspectRatio_height']) ? View::escape($_GET['aspectRatio_height']) : '';
?>
<div>
	<h2>Search</h2>
	<form method="get" action="">
		<fieldset>
			<input name="search" value="<?php echo $search; ?>" />
			<button type="submit">
				<i class="fa fa-search"></i>
				Search
			</button>
		</fieldset>
		<fieldset>
			<?php
				$currentUrl = new Url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
				$facets = &$this->solrObject->facet_counts->facet_fields;

				foreach (Search::$facetFields as $field=>$name) {
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
		<fieldset>
			<div><h3>Aspect Ratio</h3>
				<table>
					<tr><th>Width</th>
						<td><input name="aspectRatio_width" value="<?php echo $width; ?>" size="4" /></td>
					</tr>
					<tr><th>Height</th>
						<td><input name="aspectRatio_height" value="<?php echo $height; ?>" size="4" /></td>
					</tr>
				</table>
			</div>
		</fieldset>
	</form>
</div>
