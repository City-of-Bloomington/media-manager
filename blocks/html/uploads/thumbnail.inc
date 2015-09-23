<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Image;
use Application\Models\Media;

$size = Media::SIZE_THUMBNAIL;
$directory = SITE_HOME.'/uploads/'.$_SESSION['USER']->getUsername();

$filename = $_GET['image'];

if (!is_file("$directory/$size/$filename")) {
	Image::saveDerivative("$directory/$_GET[image]", $size);
}

header("Content-type: image/png");
readfile("$directory/$size/$filename");
