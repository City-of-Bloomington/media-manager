<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Media $this->media
 */
use Application\Models\Person;
use Application\Models\Media;
use Application\Models\DerivativesTable;

$fields = ['id', 'title', 'filename', 'width', 'height', 'aspectRatio', 'mime_type', 'description'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = self::escape($this->media->$get());
}
?>
<section id="mediaView">
    <header>
        <h1><?= $title; ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('media', 'edit')) {
                echo $helper->buttonLink(
                    self::generateUri('media.update', ['id'=>$id]),
                    $this->_('media_edit'),
                    'edit'
                );
            }
            if (Person::isAllowed('media', 'delete')) {
                echo $helper->buttonLink(
                    self::generateUri('media.delete', ['id'=>$id]),
                    $this->_('media_delete'),
                    'delete'
                );
            }
            ?>
        </div>
    </header>
    <div class="medium">
        <a href="<?= $this->media->getUri(); ?>">
            <img src="<?= $this->media->getUri(Media::SIZE_MEDIUM); ?>" />
        </a>
    </div>
    <table>
        <tr><th><?= $this->_('title'); ?></th>
            <td><?= $title; ?></td>
        </tr>
        <tr><th><?= $this->_('department'); ?></th>
            <td><?php
                    $dept = $this->media->getDepartment();
                    $name = self::escape($dept);
                    $uri  = self::generateUri('departments.view', ['id'=>$dept->getId()]);
                    echo "<a href=\"$uri\">$name</a>";
                ?>
            </td>
        </tr>
        <tr><th><?= $this->_('filename'); ?></th>
            <td><?= $filename; ?></td>
        </tr>
        <?php
            $tags = $this->media->getTags();
            if (count($tags)) {
                $t = self::escape(implode(', ', $tags));
                echo "
                <tr><th>{$this->_('tags')}</th>
                    <td>$t</td>
                </tr>
                ";
            }

        ?>
        <tr><th><?= $this->_('size'); ?></th>
            <td><?= "{$width}x{$height}"; ?></td>
        </tr>
        <tr><th><?= $this->_('aspectRatio'); ?></th>
            <td><?= $aspectRatio; ?></td>
        </tr>
        <tr><th><?= $this->_('mime_type'); ?></th>
            <td><?= $mime_type; ?></td>
        </tr>
        <tr><th><?= $this->_('description'); ?></th>
            <td><?= $description; ?></td>
        </tr>
    </table>

    <?php
        $table = new DerivativesTable();
        $sizes = $table->find();
        if (count($sizes)) {
            $userCanEditDerivatives = Person::isAllowed('media', 'saveDerivative');
            $action = self::generateUri('media.saveDerivative');

            foreach ($sizes as $size) {
                $name = self::escape($size->getName());
                $url  = $this->media->getUrl($size->getSize());
                echo "
                <article>
                    <a href=\"$url\"><img src=\"$url\" /></a>
                    <h1>$name</h1>
                    <div><a href=\"$url\">$url</a></div>
                ";
                    if ($userCanEditDerivatives) {
                        echo "
                        <form method=\"post\" action=\"$action\" enctype=\"multipart/form-data\">
                            <fieldset>
                                <input name=\"derivativeFile\" type=\"file\" />
                                <input name=\"id\"         type=\"hidden\" value=\"$id\" />
                                <input name=\"derivative\" type=\"hidden\" value=\"{$size->getSize()}\" />
                                <button type=\"submit\"><i class=\"fa fa-upload\"></i> {$this->_('upload')}</button>
                            </fieldset>
                        </form>
                        ";
                    }
                echo "
                </article>
                ";
            }
        }
    ?>
</section>
