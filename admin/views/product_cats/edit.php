<?php $this->include_view("products/main_header.php"); ?>
<div class="focus-box"> 
    <h2>ניהול תיקיית מוצרים</h2>
    <div class="eject-box">
        <a class="back-link" href="<?= inner_url('product_cats/list/') ?>">חזרה לרשימת התיקיות</a>
    </div>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>