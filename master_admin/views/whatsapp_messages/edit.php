<h2>עריכת איש קשר בווטסאפ</h2>
<div class="item-info focus-box">
    <b>טלפון ווטסאפ: </b><?= $this->data['item_info']['contact_phone_wa_id'] ?>
    <br/>
    <b>שם ווטסאפ: </b><?= $this->data['item_info']['contact_wa_name'] ?>
</div>
<div class="eject-box">
    <a href="<?= $this->eject_url() ?>">חזרה לרשימת השיחות</a>
</div>
<div class="focus-box">
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
