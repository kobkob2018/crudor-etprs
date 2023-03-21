<?php $this->include_view("supplier_cubes/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת הספקים</a>
    </div>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
