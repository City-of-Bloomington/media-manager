<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->user
 * @param array $this->data
 */
namespace Application\Models;

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
				<th>Department</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$c = 0;
			foreach ($directory as $file) {
				if ($file->isFile()) {
					echo "
					<tr><th><img src=\"".BASE_URI."/uploads/thumbnail?image=$file\" /></th>
						<td><input name=\"import[$file][title]\" /></td>
						<td><input name=\"import[$file][tags]\"  /></td>
						<td><select name=\"import[$file][department_id]\">
					";
							$table = new DepartmentTable();
							$list = $table->find();
							foreach ($list as $d) {
								$name = View::escape($d);
								$selected = $_SESSION['USER']->getDepartment_id()==$d->getId()
									? 'selected="selected"'
									: '';
								echo "<option value=\"{$d->getId()}\" $selected>$name</option>";
							}
					echo "
							</select>
						</td>
						<td>
					";
							if (isset($_SESSION['importErrors']["$file"])) {
								echo $_SESSION['importErrors']["$file"]->getMessage();
							}
					echo "
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
