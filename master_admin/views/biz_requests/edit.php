<?php $biz_request = $this->data['item_info']; ?>
<div class="eject-box">
    <a href="<?= $this->eject_url() ?>">חזרה לרשימה</a> | <a href="<?= inner_url("biz_requests/view/") ?>?row_id=<?= $biz_request['id'] ?>">שליחה ללקוחות</a>
</div>

<h2>עריכת בקשה להצעת מחיר</h2>

<div class="focus-box">
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
