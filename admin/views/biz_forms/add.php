<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <h3>בדף זה לא מוגדר טופס</h3>
    <h4>דף זה משתמש בהגדרות הטופס של דף הבית של האתר</h4>
</div>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
    <h3>הוספת טופס</h3>
    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
