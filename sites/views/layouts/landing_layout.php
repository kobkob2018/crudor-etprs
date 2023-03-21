<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  <script type="text/javascript">
        const cookie_prefix = "<?= get_config('cookie_prefix') ?>";
    </script>
  		<base href="<?= outer_url(); ?>" />
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	
		<link rel="shortcut icon" type="image/x-icon" href="style/image/favicon.ico">
		<link rel="stylesheet" href="<?= styles_url("style/css/site.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		<script src="<?= styles_url("style/js/site.js") ?>?v=<?= get_config("cash_version") ?>"></script>
		<script src="<?= styles_url("style/js/accessibility.js") ?>?v=<?= get_config("cash_version") ?>"></script>
		<link rel="stylesheet" href="<?= styles_url("style/css/side-drawer.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		<link rel="stylesheet" href="<?= styles_url("style/css/accessibility.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		
		<link rel="stylesheet" href="<?= styles_url("style/css/icons.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />	

		<title><?= $this->data['page_meta_title']; ?></title>
		<?php $this->include_view('registered_scripts/head.php'); ?>
		<?php if($this->data['page_style'] && $this->data['page_style']['styling_tags'] != ''): ?>
			<?= $this->data['page_style']['styling_tags'] ?>
		<?php endif; ?>
  </head>
  <body style="direction:rtl; text-align:right;" class="<?= $this->body_class ?>">
	<?php $this->print_body();  ?>
	<?php $this->include_view('registered_scripts/foot.php'); ?>

  </body>
<html>