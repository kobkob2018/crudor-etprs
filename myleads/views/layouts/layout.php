<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  		<base href="<?= outer_url(); ?>" />
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	
		<link rel="shortcut icon" type="image/x-icon" href="style/image/favicon.ico">

		<script type="text/javascript" src="<?= styles_url('style/v1/bootstrap_2.3.2/jquery.min.js') ?>"></script>
        <script type="text/javascript" src="<?= styles_url('style/v1/bootstrap_2.3.2/jquery.validate.js') ?>"></script>
        <script type="text/javascript" src="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap.min.js') ?>"></script>
        <link rel="stylesheet" href="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap.min.css') ?>"  type="text/css"  />
        <link rel="stylesheet" href="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap.rtl.css') ?>"  type="text/css"  />
        <link rel="stylesheet" href="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap-responsive.min.css') ?>"  type="text/css"  />
        <link rel="stylesheet" href="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap-responsive.rtl.css') ?>"  type="text/css"  />
        <link rel="stylesheet" href="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap-datepicker.min.css') ?>"  type="text/css"  />

        <script type="text/javascript" src="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap-datepicker.min.js') ?>"></script>
        <script type="text/javascript" src="<?= styles_url('style/v1/bootstrap_2.3.2/bootstrap-datepicker.he.min.js') ?>"></script>


		<script src="<?= styles_url('style/v1/js/main.js') ?>?v=<?= get_config("cash_version") ?>"></script>
		<link rel="stylesheet" href="<?= styles_url('style/v1/css/main.css') ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />	

		<link rel="stylesheet" href="<?= styles_url('style/v1/css/bootstrap-multiselect.css') ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
    <script src="<?= styles_url('style/v1/js/bootstrap-multiselect.js') ?>?v=<?= get_config("cash_version") ?>"></script>
    
    <script src="<?= styles_url('style/v1/js/angular.min.js') ?>?v=<?= get_config("cash_version") ?>"></script>

    	
		<title><?= $this->data['meta_title'] ?></title>
		<?php $this->include_view('registered_scripts/head.php'); ?>
  </head>
  <body style="direction:rtl; text-align:right;" class="<?php echo $this->body_class; ?>">
	<?php $this->print_body();  ?>
	<?php $this->include_view('registered_scripts/foot.php'); ?>
  </body>
<html>