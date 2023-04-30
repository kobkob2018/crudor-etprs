<?php $this->include_view("products/main_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/products/list/") ?>">חזרה לרשימה</a>
    </div>
    <h3>הוספת מוצר</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
