<h3>פרטים כלליים לאתר: <?= $this->data['site_info']['title'] ?></h3>
<hr/>
<div id="page_form_wrap" class="focus-box form-gen page-form">
	<?php $this->include_view('form_builder/form.php'); ?>
</div>

<a href="<?= inner_url("migration_site/list/") ?>">לחץ כאן לייבוא דפים ממערכת ישנה</a>