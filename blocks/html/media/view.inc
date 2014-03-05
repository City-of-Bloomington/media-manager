<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
use Application\Models\Person;
use Application\Models\Media;
use Application\Models\DerivativesTable;
use Blossom\Classes\View;
?>
<h2><?php
		echo "{$this->media->getId()} ";

		if (Person::isAllowed('media', 'edit')) {
			$helper = $this->template->getHelper('buttonLink');
			echo $helper->buttonLink(
				BASE_URI.'/media/update?media_id='.$this->media->getId(),
				$this->_('edit_media'),
				'edit'
			);
			echo $helper->buttonLink(
				BASE_URI.'/media/delete?media_id='.$this->media->getId(),
				$this->_('delete_media'),
				'delete'
			);
		}
	?>
</h2>
<div class="medium">
	<a href="<?php echo $this->media->getUrl(); ?>">
		<img src="<?php echo $this->media->getUrl(Media::SIZE_MEDIUM); ?>" />
	</a>
</div>
<table>
	<tr><th>Title</th>
		<td><?php echo View::escape($this->media->getTitle()); ?></td>
	</tr>
	<tr><th>Department</th>
		<td><?php
				$dept = $this->media->getDepartment();
				$name = View::escape($dept);
				echo "<a href=\"{$dept->getUri()}\">$name</a>";
			?>
		</td>
	</tr>
	<tr><th>Filename</th>
		<td><?php
				echo View::escape($this->media->getFilename());
			?>
		</td>
	</tr>
	<tr><th>Tags</th>
		<td><?php
				$tags = $this->media->getTags();
				if (count($tags)) {
					$t = View::escape(implode(', ', $tags));
					echo "<div>$t</div>";
				}
			?>
		</td>
	</tr>
	<tr><th>Size</th>
		<td><?php echo "{$this->media->getWidth()}x{$this->media->getHeight()}"; ?></td>
	</tr>
	<tr><th>Format</th>
		<td><?php echo $this->media->getMime_type(); ?></td>
	</tr>
	<tr><th>Description</th>
		<td><?php echo View::escape($this->media->getDescription()); ?></td>
	</tr>
</table>

<?php
	$table = new DerivativesTable();
	$sizes = $table->find();
	if (count($sizes)) {
		echo '<table>';
		foreach ($sizes as $size) {
			$name = View::escape($size->getName());
			$url = $this->media->getUrl($size->getSize());
			echo "
			<tr><th>$name</th><td><a href=\"$url\">$url</a></td></tr>
			";
		}
		echo '</table>';
	}
?>
