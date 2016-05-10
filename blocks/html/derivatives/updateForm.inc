<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Derivative $this->derivative
 */
$fields = ['id', 'name', 'size', 'aspectRatio_width', 'aspectRatio_height'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f = self::escape($this->derivative->$get());
}

$title = $this->derivative->getId() ? $this->_('derivative_edit') : $this->_('derivative_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('derivatives.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->input(['name'=>'name', 'id'=>'name', 'label'=>$this->_('name'), 'value'=>$name, 'required'=>true]);
                echo $field->input(['name'=>'size', 'id'=>'size', 'label'=>$this->_('size'), 'value'=>$size, 'required'=>true]);

            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('aspectRatio'); ?></legend>
            <?php
                echo $field->input(['name'=>'aspectRatio_width',  'id'=>'aspectRatio_width',  'label'=>$this->_('width'),  'value'=>$aspectRatio_width,  'type'=>'number']);
                echo $field->input(['name'=>'aspectRatio_height', 'id'=>'aspectRatio_height', 'label'=>$this->_('height'), 'value'=>$aspectRatio_height, 'type'=>'number']);
            ?>
        </fieldset>
        <fieldset>
            <?php
                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(BASE_URI.'/derivatives');
            ?>
        </fieldset>
    </form>
</section>