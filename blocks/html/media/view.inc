<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
use Application\Models\Person;
use Application\Models\Media;
use Blossom\Classes\View;
?>
<h2><?php
		echo sprintf('(%s) %s',
			$this->media->getId(),
			View::escape($this->media->getTitle())
		);

		if (Person::isAllowed('media', 'edit')) {
			$helper = $this->template->getHelper('buttonLink');
			echo $helper->buttonLink(
				BASE_URI.'/media/update?media_id='.$this->media->getId(),
				$this->_('edit_media'),
				'edit'
			);
		}
	?>
</h2>
<div class="medium">
	<a href="<?php echo $this->media->getUrl(); ?>">
		<img src="<?php echo $this->media->getUrl(Media::SIZE_MEDIUM); ?>" />
	</a>
</div>
<p><?php echo View::escape($this->media->getDescription()); ?></p>
<?php
	$tags = $this->media->getTags();
	if (count($tags)) {
		$t = View::escape(implode(', ', $tags));
		echo "<div>$t</div>";
	}
?>
