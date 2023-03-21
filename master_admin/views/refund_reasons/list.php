<?php $this->include_view("refund_reasons/header.php"); ?>

<h3>ניהול סיבות זיכוי</h3>


<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('refund_reasons/add/') ?>?new=1<?= $this->add_heading_url_params() ?>">הוספת סיבה</a>
</div>
<?php if(!empty($this->data['refund_reasons'])): ?>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">תיאור הסיבה</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['refund_reasons'] as $refund_reason): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('refund_reasons/edit/') ?>?&row_id=<?= $refund_reason['id'] ?><?= $this->add_heading_url_params() ?>" title="עריכה"><?= $refund_reason['label'] ?></a>
            </div>

            <div class="col">
                <a href = "<?= inner_url('refund_reasons/delete/') ?>?&row_id=<?= $refund_reason['id'] ?><?= $this->add_heading_url_params() ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
