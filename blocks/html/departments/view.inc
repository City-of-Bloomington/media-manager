<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Department $this->department
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= self::escape($this->department->getName()); ?></h1>
        <div class="tools">
        <?php
            if (Person::isAllowed('departments', 'edit')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('departments.update', ['id'=>$this->department->getId()]),
                    $this->_('department_edit'),
                    'edit'
                );
            }
        ?>
        </div>
    </header>
</section>
