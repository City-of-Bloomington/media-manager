<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h2>Uploads</h2>
<form method="post" action="<?php echo BASE_URI; ?>/uploads" enctype="multipart/form-data">
	<fieldset><legend>Upload a single photo</legend>
		<p>Use this form to add a single photo to your pending uploads.</p>
		<input type="file" name="mediafile" />
		<button type="submit"><i class="fa fa-upload"></i> Upload</button>
	</fieldset>
</form>
