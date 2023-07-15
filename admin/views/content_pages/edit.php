<?php $this->include_view("content_pages/header.php"); ?>
<div id="page_form_wrap" class="focus-box form-gen page-form">
	<?php $this->include_view('form_builder/form.php'); ?>
</div>

<?php if($view->site_user_is('master_admin')): ?>
	<a href="<?= inner_url("pages/prepare_export/?row_id=") ?><?= $this->data['item_info']['id'] ?>">
		לחץ כאן לשליחת העתק של הדף לאתר אחר
	</a>
<?php endif; ?>