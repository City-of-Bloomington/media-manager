<?php
/**
 * @copyright 2014-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->departments
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['department', 'departments', 2]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('departments', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('departments.update'),
                    $this->_('department_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <ul>
    <?php
        foreach ($this->departments as $d) {
            $name = self::escape($d->getName());
            $uri  = self::generateUri('departments.view', ['id'=>$d->getId()]);
            echo "<li><a href=\"$uri\">$name</a></li>";
        }
    ?>
    </ul>
</section>

