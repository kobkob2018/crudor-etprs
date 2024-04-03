<h2>תבניות ווטסאפ</h2>

<div class="focus-box">
    <div class="eject-box">
    <a href="<?= inner_url("whatsapp_templates/list/") ?>">חזרה לרשימת התבניות</a>
    </div>
    <hr/>
    <h3>עריכת תבנית - <?= $this->data['item_info']['label'] ?></h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>