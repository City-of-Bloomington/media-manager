<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Media $this->media
 */
use Application\Models\DepartmentTable;
use Application\Models\Media;

$title       = self::escape($this->media->getTitle());
$description = self::escape($this->media->getDescription());
$tags        = self::escape(implode(', ', $this->media->getTags()));

$t = $this->media->getId() ? $this->_('media_edit') : $this->_('media_add');
?>
<section>
    <header>
        <h1><?= $t; ?></h1>
    </header>

    <form method="post" action="<?= self::generateUri('media.update'); ?>" enctype="multipart/form-data">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->media->getId(); ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->input(['name'=>'title', 'id'=>'title', 'label'=>$this->_('title'), 'value'=>$title]);
                echo $field->input(['name'=>'tags',  'id'=>'tags',  'label'=>$this->_('tags'),  'value'=>$tags]);
            ?>
            <dl><dt><label   for="department_id"><?= $this->_('department'); ?></label></dt>
                <dd><select name="department_id" id="department_id">
                    <?php
                        $table = new DepartmentTable();
                        $list = $table->find();
                        foreach ($list as $d) {
                            $name = self::escape($d);
                            $selected = $this->media->getDepartment_id() == $d->getId()
                                ? 'selected="selected"'
                                : '';
                            echo "<option value=\"{$d->getId()}\" $selected>$name</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
        </fieldset>

        <fieldset><legend><?= $this->_('description'); ?></legend>
            <textarea name="description" id="description"><?= $description; ?></textarea>
        </fieldset>

        <fieldset>
            <legend><?= $this->_('uploadReplacement_help', 'messages'); ?></legend>
            <input type="file" name="mediafile" />
            <div class="medium">
                <img src="<?= $this->media->getUri(Media::SIZE_MEDIUM); ?>" />
            </div>
        </fieldset>

        <fieldset>
        <?php
            $helper = $this->template->getHelper('saveAndCancelButtons');
            $uri    = self::generateUri('media.view', ['id'=>$this->media->getId()]);
            echo $helper->saveAndCancelButtons($uri);
        ?>
        </fieldset>
    </form>
</section>
