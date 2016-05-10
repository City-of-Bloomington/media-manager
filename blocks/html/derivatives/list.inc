<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->derivatives
 */
use Application\Models\Media;
use Application\Models\Person;

$userCanEdit = Person::isAllowed('derivatives', 'edit');
?>
<section>
    <header>
        <h1><?= $this->_(['derivative', 'derivatives', 2]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('derivatives', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('derivatives.update'),
                    $this->_('derivative_add'),
                    'add'
                );
            }
        ?>
        </div>
	</header>
    <table>
        <thead>
            <tr><th><?= $this->_('name'); ?></th>
                <th><?= $this->_('size'); ?></th>
                <th><?= $this->_('aspectRatio'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr><td>SIZE_THUMBNAIL</td>
                <td><?= Media::SIZE_THUMBNAIL; ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr><td>SIZE_MEDIUM</td>
                <td><?= Media::SIZE_MEDIUM; ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php
                foreach ($this->derivatives as $s) {
                    $editButton = '';
                    if (Person::isAllowed('derivatives', 'edit')) {
                        $editButton = $helper->buttonLink(
                            self::generateUri('derivatives.update', ['id'=>$s->getId()]),
                            $this->_('derivative_edit'),
                            'edit'
                        );
                    }
                    $name = self::escape($s->getName());
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
</section>