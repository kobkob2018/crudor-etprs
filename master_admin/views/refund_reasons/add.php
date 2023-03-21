<?php $this->include_view("refund_reasons/header.php"); ?>
<div id="page_form_wrap" class="focus-box form-gen page-form">
	<div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
	<h3>הוספת סיבת זיכוי</h3>
	<?php $this->include_view('form_builder/form.php'); ?>
</div>