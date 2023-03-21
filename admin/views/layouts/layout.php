<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  		<base href="<?= outer_url(); ?>" />
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	
		<link rel="shortcut icon" type="image/x-icon" href="style/image/favicon.ico">

		<script src="<?= styles_url('style/js/admin.js') ?>?v=<?= get_config("cash_version") ?>"></script>
		<link rel="stylesheet" href="<?= styles_url('style/css/admin.css') ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />	

		<title><?= $this->data['meta_title'] ?></title>
		<?php $this->include_view('registered_scripts/head.php'); ?>
  </head>
  <body style="direction:rtl; text-align:right;" class="<?php echo $this->body_class; ?>">
	<?php $this->print_body();  ?>
	<?php $this->include_view('registered_scripts/foot.php'); ?>
  </body>
<html>