<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Image;
use Application\Models\Media;

$size = '60';
$directory = DATA_HOME.'/data/uploads/'.$_SESSION['USER']->getUsername();

preg_match(Media::REGEX_FILENAME_EXT, $_GET['image'], $matches);
$filename = $matches[1];

if (!is_file("$directory/$size/$filename.png")) {
	Image::saveDerivative("$directory/$_GET[image]", $size);
}

header("Content-type: image/png");
readfile("$directory/$size/$filename.png");
