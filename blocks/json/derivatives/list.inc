<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $this->derivatives
 */
use Application\Models\Media;

$out = [
	'SIZE_THUMBNAIL'=> ['size' => Media::SIZE_THUMBNAIL],
	'SIZE_MEDIUM'   => ['size' => Media::SIZE_MEDIUM   ]
];

foreach ($this->derivatives as $d) {
	$name = $d->getName();

	$out[$name]['size'] = $d->getSize();

	if ($d->getAspectRatio_width() && $d->getAspectRatio_height()) {
		$out[$name]['aspectRatio'] = [
			'width' => $d->getAspectRatio_width (),
			'height'=> $d->getAspectRatio_height()
		];
	}
}
echo json_encode($out, JSON_NUMERIC_CHECK);
