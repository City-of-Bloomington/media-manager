<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $this->media
 */
use Application\Models\Media;
use Application\Models\Person;
use Blossom\Classes\View;

$userCanEdit = Person::isAllowed('media', 'update');

if (count($this->media)) {
	echo "<div class=\"contactSheet\">";

	foreach($this->media as $media) {
		$title = View::escape($media->getTitle());

		$editButton = '';
		if ($userCanEdit) {
			$h = $this->template->getHelper('buttonLink');
			$editButton = $h->buttonLink(
				BASE_URI.'/media/update?media_id='.$media->getId(),
				$this->_('edit_media'),
				'edit'
			);
		}

		$filename = View::escape($media->getFilename());
		echo "
		<div class=\"media\">
			<h3>{$media->getId()} $editButton</h3>
			<div class=\"thumbnail\">
				<a href=\"".BASE_URI."/media/view?media_id={$media->getId()}\">
					<img src=\"{$media->getUrl(Media::SIZE_THUMBNAIL)}\" />
				</a>
			</div>
			<table>
				<tr><td>$title</td></tr>
				<tr><td>{$media->getWidth()}x{$media->getHeight()}</td></tr>
				<tr><td>{$media->getMime_type()}</td></tr>
				<tr><td>$filename</td></tr>
			</table>
		</div>
		";
	}
	echo "</div>";
}
else { echo "<p>No Photos Found.</p>"; }
