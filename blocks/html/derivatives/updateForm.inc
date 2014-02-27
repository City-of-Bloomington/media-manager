<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Derivative $this->derivative
 */
use Blossom\Classes\View;

$name = View::escape($this->derivative->getName());
$title = $this->derivative->getId() ? $this->_('edit_derivative') : $this->_('add_derivative');
?>
<h2><?php echo $title; ?></h2>
<form method="post" action="<?php echo BASE_URI; ?>/derivatives/update">
	<fieldset>
		<input name="derivative_id" type="hidden" value="<?php echo $this->derivative->getId(); ?>" />

		<table>
			<tr><td><label for="name" class="required"><?php echo $this->_('name'); ?></label></td>
				<td><input name="name" id="name" value="<?php echo $name; ?>" /></td>
			</tr>
			<tr><td><label for="size" class="required"><?php echo $this->_(['size', 'sizes', 1]); ?></label></td>
				<td><input name="size" id="size" value="<?php echo $this->derivative->getSize(); ?>" /></td>
			</tr>
		</table>
		<?php
			$h = $this->template->getHelper('saveAndCancelButtons');
			echo $h->saveAndCancelButtons(BASE_URI.'/derivatives');
		?>
	</fieldset>
</form>
