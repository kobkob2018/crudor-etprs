<?php $this->include_view("products/main_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/product_subs/list/") ?>">חזרה לרשימת תתי תיקיות</a>
    </div>
    <hr/>
    <h3>הוספת תת תיקיית מוצרים</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>