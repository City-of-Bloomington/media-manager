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
	echo '<table>';

	foreach($this->media as $media) {
		$filename = View::escape($media->getFilename());
		$title    = View::escape($media->getTitle());
		if (!$title) { $title = $filename; }

		$uri = $media->getUri(Media::SIZE_THUMBNAIL);
		echo "
		<tr><th><img src=\"$uri\" /></th>
			<td><a href=\"".BASE_URI."/media/view?media_id={$media->getId()}\">$title</a></td>
			<td>{$media->getMime_type()}</td>
			<td>$filename</td>
		</tr>
		";
	}
	echo "</table>";
}
else { echo "<p>No Photos Found.</p>"; }
