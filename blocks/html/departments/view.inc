<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Department $this->department
 */
use Blossom\Classes\View;

$name = View::escape($this->department->getName());
?>
<h2><?php
		echo View::escape($this->department->getName());

		$helper = $this->template->getHelper('buttonLink');
		echo $helper->buttonLink(
			BASE_URI.'/departments/update?department_id='.$this->department->getId(),
			$this->_('edit_department'),
			'edit'
		);
	?>
</h2>
