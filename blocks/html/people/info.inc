<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
use Application\Models\Person;
use Blossom\Classes\View;

$username = $this->person->getUsername();
if (!$username && Person::isAllowed('users')) {
	$helper = $this->template->getHelper('buttonLink');
	$username = $helper->buttonLink(
		BASE_URI.'/users/update?user_id='.$this->person->getId(),
		$this->translate('create_account'),
		'add'
	);
}

$name = View::escape($this->person->getFullname());
echo <<<EOT
<div class="personInfo">
	<h2>$name</h2>
	<table>
		<tr><th>Email</th>
			<td>{$this->person->getEmail()}</td>
		</tr>
		<tr><th>Username</th>
			<td>$username</td>
		</tr>
	</table>
</div>
EOT;
