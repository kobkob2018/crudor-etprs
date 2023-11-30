
<h3>
    מאפייניי פורטל של לקוח: <?= $this->data['user_info']['biz_name'] ?>
</h3>
<div class="eject-box">
    <a href="<?= inner_url("site_users/list/") ?>">חזרה לרשימה</a>
</div>
<?php $this->include_view('site_users/header.php'); ?>
<div class="focus-box">
    <h3>מאפייני פורטל</h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>