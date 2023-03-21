<?php $this->include_view("quotes/cat_header.php"); ?>
<div class="focus-box warning-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת התיקיות</a>
    </div>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <h2>מחיקת תיקייה</h2>
        <h3>בחר לאן להעביר את הצעות המחיר שבתיקייה</h3>

        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>