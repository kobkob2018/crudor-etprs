<?php $this->include_view("cities/city_header.php"); ?>
<div class="focus-box">
    <h3>עריכת אזור\עיר <?= $this->data['item_info']['label'] ?></h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
