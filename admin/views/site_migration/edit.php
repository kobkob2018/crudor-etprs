<div class="focus-box">
    <h3>תיאום ייבוא לאתר מהמערכת הישנה</h3>
    <?php $this->include_view("site_migration/header.php"); ?>
    <div class="focus-box">

        <b>דומיין:</b> <?= $this->data['item_info']['old_domain'] ?> <br/>
        <b>מספר:</b> <?= $this->data['item_info']['old_id'] ?> <br/>
        <b>unk:</b> <?= $this->data['item_info']['old_unk'] ?> <br/>
        <b>כותרת:</b> <?= $this->data['item_info']['old_title'] ?> <br/>

    </div>
    <hr/>
    <div id="biz_form_form_wrap" class="form-gen page-form">
        <h3>החלפת אתר לייבוא</h3>
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>


