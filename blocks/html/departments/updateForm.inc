<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Department $this->department
 */

$id = $this->department->getId();
$name = self::escape($this->department->getName());

$title = $this->department->getId() ? $this->_('department_edit') : $this->_('department_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>

    <form method="post" action="<?php echo BASE_URI; ?>/departments/update">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->input(['name'=>'name', 'id'=>'name', 'label'=>$this->_('name'), 'value'=>$name, 'required'=>true]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(BASE_URI.'/departments');
            ?>
        </fieldset>
    </form>
</section>