<?php defined('SYSPATH') or die('No direct script access.'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard default template</title>
	<meta name="description" content="">
	<meta name="author" content="Wojciech Duda">
	<link rel="stylesheet" href="<?php echo URL::base(); ?>assets/css/smoothness/jquery-ui-1.10.3.custom.min.css" />
	<link rel="stylesheet" href="<?php echo URL::base(); ?>assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo URL::base(); ?>assets/css/dashboard.css" />
	<link rel="stylesheet" href="<?php echo URL::base(); ?>assets/css/styles.css" />

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
<?php echo ( ! empty($header_script)) ? $header_script : ''; ?>
</head>
<body class="<?php echo (isset($body_class) ? $body_class : ''); ?>">

<div class="full-page-wrapper">
<?php if (isset($top_menu)) echo $top_menu; ?>
	<div class="container-fluid">
	<div class="row">
<?php if (isset($side_menu)) echo $side_menu; ?>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<?php if (isset($content)) echo $content; ?>
		</div>
	</div>
	</div>
</div>

<!--
<div class="footer">
	<div class="container-fluid">
		<p class="text-muted">&copy; 2014</p>
	</div>
</div>
 -->
<script type="text/javascript" src="<?php echo URL::base(); ?>assets/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo URL::base(); ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?php echo URL::base(); ?>assets/js/jquery.ui.datepicker-pl.min.js"></script>
<script type="text/javascript" src="<?php echo URL::base(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo URL::base(); ?>assets/js/scripts.js"></script>
<?php echo ( ! empty($footer_script)) ? $footer_script : ''; ?>
</body>
</html>
