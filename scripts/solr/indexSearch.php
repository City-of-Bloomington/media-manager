<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Media;
use Application\Models\Search;
use Blossom\Classes\Database;

include '../../configuration.inc';
$search = new Search();
$search->solrClient->deleteByQuery('*:*');
$search->solrClient->commit();

$sql = 'select * from media';
$zend_db = Database::getConnection();
$result = $zend_db->query($sql)->execute();

$c = 0;
foreach ($result as $row) {
	$media = new Media($row);
	$search->add($media);
	$c++;
	echo "$c: {$media->getId()}\n";
}
echo "Committing\n";
$search->solrClient->commit();
echo "Optimizing\n";
$search->solrClient->optimize();
echo "Done\n";
