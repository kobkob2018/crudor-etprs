<?php $this->include_view("users/header.php"); ?>
<h3>שיגור תשלום</h3>
<div class="eject-box">
	<a href="<?= $this->eject_url() ?>">חזרה לרשימה</a>
</div>
<div id="page_form_wrap" class="focus-box form-gen page-form">
	<?php $this->include_view('form_builder/form.php'); ?>
</div>