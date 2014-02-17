<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
use Application\Models\Media;
use Blossom\Classes\View;

$title = View::escape($this->media->getTitle());
$description = View::escape($this->media->getDescription());
?>
<h2>Edit Media</h2>
<form method="post" action="<?php echo BASE_URI; ?>/media/update">
	<fieldset>
		<input name="media_id" type="hidden" value="<?php echo $this->media->getId(); ?>" />

		<table>
			<tr><td><label for="title">Title</label></td>
				<td><input name="title" id="title" value="<?php $title; ?>" /></td>
			</tr>
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
</form>
<div class="medium">
	<img src="<?php echo $this->media->getUri(Media::SIZE_MEDIUM); ?>" />
</div>
