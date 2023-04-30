<?php $this->include_view("gallery_images/gallery_header.php"); ?>
<div class="focus-box warning-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת הגלריות</a>
    </div>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <h2>מחיקת גלריה</h2>
        <h3>בחר לאן להעביר את התמונות בגלריה</h3>

        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>