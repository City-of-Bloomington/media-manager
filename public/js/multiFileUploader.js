"use strict";
YUI().use('uploader', function(Y) {
	if (Y.Uploader.TYPE != 'none' && !Y.UA.ios) {
		var uploadDone = false,
			uploader = new Y.Uploader({
			width:'100px',
			height:'30px',
			multipleFiles: true,
			simLimit:2
		});
		uploader.set('fileFieldName', 'batchFile');

		if (Y.Uploader.TYPE == 'html5') {
			uploader.set('dragAndDropArea', 'body');
		}
		uploader.render('#selectFilesButtonContainer');

		uploader.after('fileselect', function (e) {
			var files = e.fileList,
				table = Y.one('#filenames tbody');

			if (files.length > 0 && Y.one('#nofiles')) {
				Y.one('#nofiles').remove();
			}

			if (uploadDone) {
				uploadDone = false;
				table.setHTML('');
			}

			Y.each(files, function (f) {
				table.append(
					'<tr id="' + f.get('id')   + '_row">' +
						'<td>' + f.get('name') + '</td>' +
						'<td>' + f.get('size') + '</td>' +
						'<td class="percentDone"></td>' +
					'</tr>'
				);
			});
		});

		uploader.on('uploadprogress', function (e) {
			var row = Y.one('#' + e.file.get('id') + '_row');
			row.one('.percentDone').set('text', e.percentLoaded + '%');
		});

		uploader.on('uploadstart', function (e) {
		});

		uploader.on('uploadcomplete', function (e) {
			var row = Y.one('#' + e.file.get('id') + '_row');
			row.one('.percentDone').set('text', 'Finished');
		});

		uploader.on('totaluploadprogress', function (e) {
			Y.one('#overallProgress').setHTML('Total uploaded: ' + e.percentLoaded + '%');
		});

		uploader.on('alluploadscomplete', function (e) {
			window.location.reload(true);
			/*
			uploader.set('enabled', true);
			uploader.set('fileList', []);
			Y.one('#overallProgress').set('text', 'Uploads complete');
			uploadDone = true;
			*/
		});

		Y.one('#uploadFilesButton').on('click', function () {
			if (!uploadDone && uploader.get('fileList').length > 0) {
				uploader.uploadAll(APPLICATION.BASE_URL + '/uploads');
			}
		});
	}
	else {
		Y.one('#uploaderContainer').set('text', 'Your browser does not support multi-file uploading');
	}
});
