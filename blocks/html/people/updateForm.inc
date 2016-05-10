<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\Models\DepartmentTable;

$fields = ['firstname','lastname','email', 'department_id'];
foreach ($fields as $field) {
	$get = 'get'.ucfirst($field);
	$$field = self::escape($this->person->$get());
}
$title = $this->person->getId() ? self::escape('Edit '.$this->person->getFirstname()) : $this->_('person_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('people.update'); ?>">
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->person->getId(); ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->input(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$firstname, 'required'=>true]);
                echo $field->input(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname'),  'value'=>$lastname,  'required'=>true]);
                echo $field->input(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'),     'value'=>$email]);

                echo "
                <dl><dt><label   for=\"department_id\">{$this->_('department')}</label></dt>
                    <dd><select name=\"department_id\">
                ";
                        $table = new DepartmentTable();
                        $list = $table->find();
                        foreach ($list as $d) {
                            $name = self::escape($d->getName());
                            $selected = $d->getId() == $department_id ? 'selected="true"' : '';
                            echo "<option value=\"{$d->getId()}\" $selected>$name</option>";
                        }
                echo "
                        </select>
                    </dd>
                </dl>
                ";

				$h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons(BASE_URI.'/people');
			?>
		</fieldset>
	</form>
</section>
