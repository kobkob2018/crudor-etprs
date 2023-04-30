<?php $this->include_view("products/main_header.php"); ?>

<?php $this->include_view("product_subs/header.php"); ?>
<div class="focus-box"> 
    <h2>עריכת תת תיקייה</h2>
    <div class="eject-box">
        <a class="back-link" href="<?= inner_url('product_subs/list/') ?>">חזרה לרשימת תתי תיקיות</a>
    </div>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>