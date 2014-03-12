<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
use Application\Models\DerivativesTable;

$m = [
	'id'              => $this->media->getId(),
	'filename'        => $this->media->getFilename(),
	'internalFilename'=> $this->media->getInternalFilename(),
	'mime_type'       => $this->media->getMime_type(),
	'media_type'      => $this->media->getMedia_type(),
	'title'           => $this->media->getTitle(),
	'description'     => $this->media->getDescription(),
	'md5'             => $this->media->getMd5(),
	'uploaded'        => $this->media->getUploaded('c'),
	'width'           => $this->media->getWidth(),
	'height'          => $this->media->getHeight()
];
$table = new DerivativesTable();
$sizes = $table->find();
foreach ($sizes as $s) {
	$m['derivatives'][] = [ $s->getName() => $s->getSize() ];
}
echo json_encode($m, JSON_NUMERIC_CHECK);
