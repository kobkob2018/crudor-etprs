<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה לרשימת השיחות</a>
    </div>
    <h2>עריכת איש קשר בווטסאפ</h2>
    <div class="item-info focus-box">
        <b><?php print_r_help($this->data); ?></b>
    </div>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
