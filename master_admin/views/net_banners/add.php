<?php $this->include_view("net_directories/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת הבאנרים בתיקייה</a>
    </div>
    <h3>הוספת באנר</h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
