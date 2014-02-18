<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$this->template->addToAsset('scripts', YUI.'/yui/yui-min.js');
$this->template->addToAsset('scripts', BASE_URI.'/js/multiFileUploader.js');
?>
<h2>Uploads</h2>
<form method="post" action="<?php echo BASE_URI; ?>/uploads" enctype="multipart/form-data">
	<fieldset><legend>Upload a single photo</legend>
		<p>Use this form to add a single photo to the system</p>
		<input type="file" name="mediafile" />
		<button type="submit"><i class="fa fa-upload"></i> Import</button>
	</fieldset>
</form>

<h3>Batch Uploader</h3>
<div id="uploaderContainer">
	<div>
		<button type="button" id="uploadFilesButton">
			<i class="fa fa-upload"></i>
			Upload Files
		</button>
		<div id="overallProgress"></div>
	</div>
	<table id="filenames">
		<thead>
			<tr><th>File name</th>
				<th>File size</th>
				<th>Percent Uploaded</th>
			</tr>
		</thead>
		<tbody>
			<tr id="nofiles">
				<td colspan="3" id="ddmessage">Drag and drop files here</td>
			</tr>
		</tbody>
	</table>
	<div id="selectFilesButtonContainer"></div>
</div>
