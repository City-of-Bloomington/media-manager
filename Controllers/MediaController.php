<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\Media;
use Application\Models\MediaTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class MediaController extends Controller
{
	public function index()
	{
	}

	/**
	 * Create and cache a resized image file
	 *
	 * @param REQUEST media_id
	 * @param REQUEST size
	 */
	public function resize()
	{
		$this->template->setFilename('media');
		try {
			$media = new Media($_REQUEST['media_id']);
			$size = !empty($_REQUEST['size']) ? (int)$_REQUEST['size'] : null;
			$this->template->blocks[] = new Block(
				'media/image.inc',
				array('media'=>$media, 'size'=>$size)
			);
		}
		catch (Exception $e) {
			header('HTTP/1.1 404 Not Found', true, 404);
		}
	}
}
