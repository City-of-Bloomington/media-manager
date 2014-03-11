<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\MediaTable;

include '../../../configuration.inc';

$table = new MediaTable();
$list = $table->find();
$c = 1;
foreach ($list as $media) {
    $media->setWidth ($media->getImageWidth ());
    $media->setHeight($media->getImageHeight());
    $media->save();

    echo "$c: {$media->getId()}\n";
    $c++;
}
