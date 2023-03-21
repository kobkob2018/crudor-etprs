<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <h3>עריכת טופס <?= $this->data['item_info']['title'] ?></h3>
    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
