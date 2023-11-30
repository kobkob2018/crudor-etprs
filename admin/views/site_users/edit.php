<h3>עריכת תפקיד משתמש: <?= $this->data['site_user_info']['full_name'] ?></h3>
<div class="eject-box">
	<a href="<?= inner_url("site_users/list/") ?>">חזרה לרשימה</a>
</div>
<?php $this->include_view('site_users/header.php'); ?>
<hr/>
<div id="site_user_form_wrap" class="focus-box form-gen page-form">
	<h3>ניהול הרשאות משתמש</h3>
	<?php $this->include_view('form_builder/form.php'); ?>
</div>
