<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param array $this->media
 */
use Application\Models\Media;
use Application\Models\Person;

$userCanEdit = Person::isAllowed('media', 'edit');

if (count($this->media)) {
	echo "<section class=\"contactSheet\">";

	$fields = ['id', 'title', 'filename', 'width', 'height', 'mime_type'];

	foreach($this->media as $media) {
        foreach ($fields as $f) {
            $get = 'get'.ucfirst($f);
            $$f  = self::escape($media->$get());
        }

		$editButton = '';
		if ($userCanEdit) {
			$h = $this->template->getHelper('buttonLink');
			$editButton = $h->buttonLink(
                self::generateUri('media.update', ['id'=>$id]),
				$this->_('media_edit'),
				'edit'
			);
		}

		$uri = self::generateUri('media.view', ['id'=>$id]);
		echo "
		<article class=\"media\">
            <header>
                <h1>$id</h1>
                <div class=\"tools\">$editButton</div>
            </header>
			<div class=\"thumbnail\">
				<a href=\"$uri\">
					<img src=\"{$media->getUrl(Media::SIZE_THUMBNAIL)}\" />
				</a>
			</div>
			<table>
				<tr><td>$title</td></tr>
				<tr><td>{$width}x{$height}</td></tr>
				<tr><td>$mime_type</td></tr>
				<tr><td>$filename</td></tr>
			</table>
		</article>
		";
	}
	echo "</section>";
}
else { echo "<p>No Photos Found.</p>"; }
