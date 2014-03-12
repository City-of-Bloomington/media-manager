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
<table>
	<thead>
		<tr><th>Name</th><th>Size</th><th>Aspect Ratio</th><th></th></tr>
	</thead>
	<tbody>
		<tr><td>SIZE_THUMBNAIL</td>
			<td><?php echo Media::SIZE_THUMBNAIL; ?></td>
			<td></td>
			<td></td>
		</tr>
		<tr><td>SIZE_MEDIUM</td>
			<td><?php echo Media::SIZE_MEDIUM; ?></td>
			<td></td>
			<td></td>
		</tr>
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
				$aspectRatio = '';
				if ($s->getAspectRatio_width() && $s->getAspectRatio_height()) {
					$aspectRatio = "{$s->getAspectRatio_width()}x{$s->getAspectRatio_height()} ({$s->getAspectRatio()})";
				}
				echo "
				<tr><td>$name</td>
					<td>{$s->getSize()}</td>
					<td>$aspectRatio</td>
					<td>$editButton</td>
				</tr>
				";
			}
		?>
	</tbody>
</table>
