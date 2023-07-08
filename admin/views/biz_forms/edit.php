<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <h3>עריכת טופס <?= $this->data['item_info']['title'] ?></h3>

    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>

<div class="focus-box">
    <h3>באפשרותך למחוק את הטופס, כך שדף זה ישתמש באותו טופס המוגדר בדף הבית</h3>
    <a class="button-focus" href="<?= $this->alt_delete_url($this->data['item_info']) ?>">
        לחץ כאן למחיקת הטופס
    </a>
</div>
