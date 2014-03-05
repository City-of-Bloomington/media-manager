<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
use Application\Models\DepartmentTable;
use Application\Models\Media;
use Blossom\Classes\View;

$title       = View::escape($this->media->getTitle());
$description = View::escape($this->media->getDescription());
$tags        = View::escape(implode(', ', $this->media->getTags()));
?>
<h2>Edit Media</h2>
<form method="post" action="<?php echo BASE_URI; ?>/media/update" enctype="multipart/form-data">
	<fieldset>
		<input name="media_id" type="hidden" value="<?php echo $this->media->getId(); ?>" />

		<table>
			<tr><td><label for="title">Title</label></td>
				<td><input name="title" id="title" value="<?php echo $title; ?>" /></td>
			</tr>
			<tr><td><label for="tags">Tags</label></td>
				<td><input name="tags" id="tags" value="<?php echo $tags; ?>" /></td>
			</tr>
			<tr><td><label for="department_id">Department</label></td>
				<td><select name="department_id" id="department_id">
					<?php
						$table = new DepartmentTable();
						$list = $table->find();
						foreach ($list as $d) {
							$name = View::escape($d);
							$selected = $this->media->getDepartment_id()==$d->getId()
								? 'selected="selected"'
								: '';
							echo "<option value=\"{$d->getId()}\" $selected>$name</option>";
						}
					?>
					</select>
				</td>
		</table>
		<div>
			<div><label for="description">Description</label></div>
			<textarea name="description" id="description" rows="3" cols="60"><?php echo $description; ?></textarea>
		</div>
		<?php
			$helper = $this->template->getHelper('saveAndCancelButtons');
			echo $helper->saveAndCancelButtons(BASE_URI.'/media/view?media_id='.$this->media->getId());
		?>
	</fieldset>
	<fieldset>
		<legend>Upload a new version of this file</legend>
		<input type="file" name="mediafile" />
		<div class="medium">
			<img src="<?php echo $this->media->getUri(Media::SIZE_MEDIUM); ?>" />
		</div>
	</fieldset>
</form>
