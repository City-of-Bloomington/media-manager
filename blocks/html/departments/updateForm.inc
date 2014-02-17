<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Department $this->department
 */
use Blossom\Classes\View;

$name = View::escape($this->department->getName());
$title = $this->department->getId() ? $this->_('edit_department') : $this->_('add_department');
?>
<h2><?php echo $title; ?></h2>
<form method="post" action="<?php echo BASE_URI; ?>/departments/update">
	<fieldset>
		<input name="department_id" type="hidden" value="<?php echo $this->department->getId(); ?>" />

		<table>
			<tr><td><label for="name" class="required">Name</label></td>
				<td><input name="name" id="name" value="<?php echo $name; ?>" /></td>
			</tr>
		</table>
		<?php
			$h = $this->template->getHelper('saveAndCancelButtons');
			echo $h->saveAndCancelButtons(BASE_URI.'/departments');
		?>
	</fieldset>
</form>
