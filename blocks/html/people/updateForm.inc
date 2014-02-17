<?php
/**
 * @copyright 2009-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\DepartmentTable;
use Blossom\Classes\View;

$fields = array('firstname','lastname','email');
foreach ($fields as $field) {
	$get = 'get'.ucfirst($field);
	$$field = View::escape($this->person->$get());
}
$title = $this->person->getId() ? View::escape('Edit '.$this->person->getFirstname()) : 'Add Someone';
?>
<div class="updatePersonForm">
	<h2><?php echo $title; ?></h2>
	<form method="post" action="<?php echo BASE_URI; ?>/people/update">
		<fieldset><legend><?php echo $this->_('info_person'); ?></legend>
			<input name="person_id" type="hidden" value="<?php echo $this->person->getId(); ?>" />

			<table>
				<tr><td><label for="firstname" class="required"><?php echo $this->_('firstname'); ?></label></td>
					<td><input name="firstname" id="firstname" value="<?php echo $firstname; ?>" /></td>
				</tr>

				<tr><td><label for="lastname" class="required"><?php echo $this->_('lastname'); ?></label></td>
					<td><input name="lastname" id="lastname" value="<?php echo $lastname; ?>" /></td>
				</tr>

				<tr><td><label for="email"><?php echo $this->_(['email', 'emails', 1]); ?></label></td>
					<td><input name="email" id="email" value="<?php echo $email; ?>" /></td>
				</tr>

				<tr><td><label for="department_id"><?php echo $this->_(['department', 'departments', 1]); ?></label></td>
					<td><select name="department_id">
						<?php
							$table = new DepartmentTable();
							$list = $table->find();
							foreach ($list as $d) {
								$name = View::escape($d->getName());
								$selected = $this->person->getDepartment_id()==$d->getId()
									? 'selected="selected"'
									: '';
								echo "<option value=\"{$d->getId()}\" $selected>$name</option>";
							}
						?>
						</select>
					</td>
				</tr>
			</table>
			<?php
				$h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons(BASE_URI.'/people');
			?>
		</fieldset>
	</form>
</div>
