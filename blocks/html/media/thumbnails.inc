<?php
/**
 * @copyright Copyright 2014 Cliff Ingham
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

		echo "
		<div class=\"thumbnail\">
			<h3>{$media->getId()}</h3>
			<a href=\"".BASE_URI."/media/view?media_id={$media->getId()}\">
				<img src=\"{$media->getUrl(Media::SIZE_THUMBNAIL)}\" />
			</a>
			<div class=\"title\">$title</div>
		</div>
		";
	}
}
else { echo "<p>No Photos Found.</p>"; }
