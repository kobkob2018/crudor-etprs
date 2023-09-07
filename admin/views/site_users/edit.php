<h3>עריכת תפקיד משתמש: <?= $this->data['site_user_info']['full_name'] ?></h3>
<div class="eject-box">
	<a href="<?= inner_url("site_users/list/") ?>">חזרה לרשימה</a>
</div>
<hr/>
<div id="site_user_form_wrap" class="focus-box form-gen page-form">
	<?php $this->include_view('form_builder/form.php'); ?>
</div>
