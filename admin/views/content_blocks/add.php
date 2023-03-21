<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
    <h3>הוספת בלוק</h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
