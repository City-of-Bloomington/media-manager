<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Templates\Helpers;

use Blossom\Classes\Template;

class ButtonLink
{
	private $template;

	/**
	 * Maps the button types to Font-Awesome css classes
	 */
	private static $types = [
		'add'   =>'fa fa-plus',
		'edit'  =>'fa fa-edit',
		'cancel'=>'fa fa-undo',
		'delete'=>'fa fa-times',
	];

	public function __construct(Template $template)
	{
		$this->template = $template;
	}

	public function buttonLink($url, $label, $type, $hideLabel=false)
	{
		$a = $hideLabel
			? '<a href="%s" class="%s"><i class="hidden-label">%s</i></a>'
			: '<a href="%s" class="btn"><i class="%s"></i> %s</a>';
		return sprintf($a, $url, self::$types[$type], $label);
	}
}
