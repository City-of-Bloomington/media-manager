<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $this->derivatives
 */
use Application\Models\Media;

$out = [
	'SIZE_THUMBNAIL'=> Media::SIZE_THUMBNAIL,
	'SIZE_MEDIUM'   => Media::SIZE_MEDIUM
];

foreach ($this->derivatives as $d) {
	$out[$d->getName()] = $d->getSize();
}
echo json_encode($out);
