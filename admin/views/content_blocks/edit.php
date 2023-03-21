<?php $this->include_view("content_pages/header.php"); ?>
<div class="focus-box">
    <div class="eject-box">
        <a href="<?= $this->eject_url() ?>">חזרה</a>
    </div>
    <h3>עריכת בלוק <?= $this->data['item_info']['label'] ?></h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
<?php if($this->data['page_style']): ?>
    <?= $this->data['page_style']['styling_tags'] ?>
<?php endif; ?>
