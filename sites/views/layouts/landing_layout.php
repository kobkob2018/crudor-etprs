<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
		<script type="text/javascript">
			const cookie_prefix = "<?= get_config('cookie_prefix') ?>";
		</script>
  		<base href="<?= outer_url(); ?>" />
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	
		<?php if(isset($this->data['page_meta_favicon'])): ?>
			<link rel="shortcut icon" type="image/x-icon" href="<?= $this->data['page_meta_favicon'] ?>">
		<?php endif; ?>
		<link rel="stylesheet" href="<?= styles_url("style/".$this->data['directions']['css_dir']."/site.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		<link rel="stylesheet" href="<?= $this->file_url_of('colors_css','colors.css') ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		<script src="<?= styles_url("style/js/site.js") ?>?v=<?= get_config("cash_version") ?>"></script>
		<script src="<?= styles_url("style/js/accessibility.js") ?>?v=<?= get_config("cash_version") ?>"></script>
		<link rel="stylesheet" href="<?= styles_url("style/".$this->data['directions']['css_dir']."/side-drawer.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		<link rel="stylesheet" href="<?= styles_url("style/".$this->data['directions']['css_dir']."/accessibility.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />
		
		<link rel="stylesheet" href="<?= styles_url("style/css/icons.css") ?>?v=<?= get_config("cash_version") ?>"  type="text/css" />	

		<title><?= $this->data['page_meta_title']; ?></title>
		<?php $this->include_view('registered_scripts/head.php'); ?>
		<?php if($this->data['page_style'] && $this->data['page_style']['styling_tags'] != ''): ?>
			<?= $this->data['page_style']['styling_tags'] ?>
		<?php endif; ?>
		<?php if($this->data['site']['disable_robots'] == '1' || isset($this->data['add_nofollow_tag'])): ?>
			<META NAME="robots" CONTENT="noindex,nofollow">
		<?php endif; ?>
		<?= $this->data['site_styling']['styling_tags'] ?>
  </head>
  <body class="<?= $this->body_class ?>">
	<?php $this->print_body();  ?>
	<?php $this->include_view('registered_scripts/foot.php'); ?>
	<?= $this->data['site_styling']['bottom_styling_tags'] ?>
  </body>
<html>