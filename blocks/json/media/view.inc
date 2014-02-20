<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
$m = [
	'id'=>$this->media->getId(),
	'filename'=>$this->media->getInternalFilename(),
	'mime_type'=>$this->media->getMime_type(),
	'media_type'=>$this->media->getMedia_type(),
	'title'=>$this->media->getTitle(),
	'description'=>$this->media->getDescription(),
	'md5'=>$this->media->getMd5()
];
echo json_encode($m);
