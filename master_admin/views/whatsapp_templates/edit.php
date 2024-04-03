<h2>חדשות האתר</h2>

<div class="focus-box">
    <div class="eject-box">
    <a href="<?= inner_url("news/list/") ?>">חזרה לרשימת החדשות</a>
    </div>
    <hr/>
    <h3>עריכת חדשה - <?= $this->data['item_info']['label'] ?></h3>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>