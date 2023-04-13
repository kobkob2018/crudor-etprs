<?php $this->include_view("users/header.php"); ?>

<h3>שיגורי תשלום ללקוח</h3>


<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('user_lounch_fee/add/') ?>?user_id=<?= $this->data['user_info']['id'] ?>">שיגור תשלום חדש</a>
</div>
<?php if(!empty($this->data['user_fee_lounches'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">מחיר כולל מע"מ</div>
            <div class="col">פרטים</div>

            <div class="col">תאריך אחרון לתשלום</div>
            <div class="col">סטטוס תשלום</div>
            <div class="col">ביטול</div>
        </div>
        <?php foreach($this->data['user_fee_lounches'] as $fee_lounch): ?>
            <div class="table-tr row user_fee_state_<?= $fee_lounch['pay_status'] ?>">
                <div class="col">
                    <?= $fee_lounch['price'] ?>
                </div>

                <div class="col">
                    <?= nl2br($fee_lounch['details']) ?>
                </div>
                <div class="col">
                    <?= hebdt($fee_lounch['until_date'],'d-m-Y') ?>
                </div>
                <div class="col">
                    <?= $this->get_label_value('pay_status',$fee_lounch) ?>
                </div>
                <div class="col">
                    <?php if($fee_lounch['pay_status'] == '0'): ?>
                        <a href = "<?= inner_url('user_lounch_fee/delete/') ?>?&row_id=<?= $fee_lounch['id'] ?>&user_id=<?= $this->data['user_info']['id'] ?>" title="בטל">בטל</a>
                    <?php elseif($fee_lounch['pay_status'] != '5'): ?>
                        לא ניתן למחיקה - תשלום בוצע
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
