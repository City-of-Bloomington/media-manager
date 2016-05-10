<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class DerivativesTable extends TableGateway
{
	public function __construct() { parent::__construct('derivatives', __namespace__.'\Derivative'); }
}
