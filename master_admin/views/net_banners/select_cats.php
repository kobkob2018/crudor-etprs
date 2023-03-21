<?php $this->include_view("net_directories/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת הבאנרים בתיקייה</a>
    </div>
    <hr/>
    <?php $this->include_view("net_banners/sub_header.php"); ?>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('tree/select_cats.php'); ?>
    </div>
</div>
