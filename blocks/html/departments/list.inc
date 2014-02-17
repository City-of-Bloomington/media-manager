<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $this->departments
 */
use Application\Models\Person;
use Blossom\Classes\View;

$userCanEdit = Person::isAllowed('departments', 'edit');
?>
<h2>
<?php
	echo $this->_(['department', 'departments', 2]);

	$helper = $this->template->getHelper('buttonLink');
	if ($userCanEdit) {
		echo $helper->buttonLink(
			BASE_URI.'/departments/update',
			$this->translate('add_department'),
			'add'
		);
	}
?>
</h2>
<ul>
<?php
	foreach ($this->departments as $d) {
		$editButton = '';
		if ($userCanEdit) {
			$editButton = $helper->buttonLink(
				BASE_URI.'/departments/update?department_id='.$d->getId(),
				$this->_('edit_department'),
				'edit'
			);
		}
		$name = View::escape($d->getName());
		echo "<li>$editButton<a href=\"{$d->getUri()}\">$name</a></li>";
	}
?>
</ul>
