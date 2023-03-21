<?php $this->include_view("refund_reasons/header.php"); ?>
<div id="page_form_wrap" class="focus-box form-gen page-form">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
    <h4>עריכת סיבת זיכוי</h4>
	<?php $this->include_view('form_builder/form.php'); ?>
</div>