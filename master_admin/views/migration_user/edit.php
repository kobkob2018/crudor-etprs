<div class="focus-box">
    <h3>תיאום ייבוא לאתר מהמערכת הישנה</h3>
    <?php $this->include_view("migration_user/header.php"); ?>
    <div class="focus-box">

        <b>unk:</b> <?= $this->data['item_info']['old_unk'] ?> <br/>
        <b>מספר:</b> <?= $this->data['item_info']['old_id'] ?> <br/>
        <b>שם:</b> <?= $this->data['item_info']['old_name'] ?> <br/>
        <b>שם מלא:</b> <?= $this->data['item_info']['old_full_name'] ?> <br/>

    </div>
    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <h3>החלפת משתמש לייבוא</h3>
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>


