<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->user
 * @param array $this->data
 */
use Application\Models\Media;
use Application\Models\UploadDirectory;
use Blossom\Classes\View;

$directory = new UploadDirectory($this->user);
if (count($directory)) {
?>
<h2>Files ready to import</h2>
<form method="post" action="<?php echo BASE_URI; ?>/uploads/import">
	<fieldset>
		<button type="submit"><i class="fa fa-upload"></i> Import</button>
		<table class="uploads">
		<thead>
			<tr><th></th>
				<th>Title</th>
				<th>Tags</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$c = 0;
			foreach ($directory as $file) {
				if ($file->isFile()) {
					echo "
					<tr><th rowspan=\"2\">
							<img src=\"".BASE_URI."/uploads/thumbnail?image=$file\" />
						</th>
						<td><input name=\"import[$file][title]\" id=\"import_{$c}_title\" /></td>
						<td><input name=\"import[$file][tags]\"  id=\"import_{$c}_tags\"  /></td>
					</tr>
					<tr><td colspan=\"2\">
							<textarea name=\"import[$file][description]\" id=\"import_{$c}_description\" rows=\"3\" cols=\"60\"></textarea>
						</td>
					</tr>
					";
				}
				$c++;
			}
		?>
		</tbody>
		</table>
	</fieldset>
</form>
<?php
}
