<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\ResultSet $this->derivatives
 */
use Application\Models\Media;
use Application\Models\Person;
use Blossom\Classes\View;

$userCanEdit = Person::isAllowed('derivatives', 'edit');
?>
<h2>
<?php
	echo $this->_(['derivative', 'derivatives', 2]);

	$helper = $this->template->getHelper('buttonLink');
	if ($userCanEdit) {
		echo $helper->buttonLink(
			BASE_URI.'/derivatives/update',
			$this->translate('add_derivative'),
			'add'
		);
	}
?>
</h2>
<ul><li>SIZE_THUMBNAIL (<?php echo Media::SIZE_THUMBNAIL; ?>)</li>
	<li>SIZE_MEDIUM    (<?php echo Media::SIZE_MEDIUM; ?>)</li>
<?php
	foreach ($this->derivatives as $s) {
		$editButton = '';
		if ($userCanEdit) {
			$editButton = $helper->buttonLink(
				BASE_URI.'/derivatives/update?derivative_id='.$s->getId(),
				$this->_('edit_derivative'),
				'edit'
			);
		}
		$name = View::escape($s->getName());
		echo "<li>$editButton $name ({$s->getSize()})</li>";
	}
?>
</ul>
