<?php $this->include_view("quotes/cat_header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("/quotes/list/") ?>?cat_id=<?= $this->data['cat_info']['id'] ?>">חזרה לרשימה</a>
    </div>
    <h3>הוספת הצעת מחיר</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
