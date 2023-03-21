<?php $this->include_view("biz_categories/biz_cat_header.php"); ?>
<div class="focus-box">
    <h3>עריכת קטגוריה <?= $this->data['item_info']['label'] ?></h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
