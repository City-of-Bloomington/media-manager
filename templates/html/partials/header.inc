<head>
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URI; ?>/css/local/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URI; ?>/css/local/layouts/<?php echo $this->layout; ?>.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URI; ?>/css/local/screen.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URI; ?>/css/Font-Awesome/css/font-awesome.css" />
	<title><?php echo isset($this->title) ? self::escape($this->title) : APPLICATION_NAME; ?></title>
	<script type="text/javascript">
		var APPLICATION = {
			BASE_URL: '<?php echo BASE_URL; ?>'
		};
	</script>
</head>
