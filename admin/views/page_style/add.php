<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
    <h3>מאפייניי עיצוב של הדף</h3>
    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
