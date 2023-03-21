<?php $this->include_view("cities/city_header.php"); ?>

<div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
</div>
<hr/>
<div id="page_form_wrap" class="focus-box form-gen page-form">
    <h3>הוספת עיר\אזור</h3>
	<?php $this->include_view('form_builder/form.php'); ?>
</div>
