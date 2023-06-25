<h2>שיגורי תשלום שלא שולמו</h2>

<?php if(!empty($this->data['user_fee_lounches'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">מחיר כולל מע"מ</div>
            <div class="col">לקוח</div>
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
                    <a href = "<?= inner_url('user_lounch_fee/list/') ?>?user_id=<?= $fee_lounch['user_id'] ?>" title="עבור לצפייה בלקוח">
                        <?= $fee_lounch['user']['full_name'] ?>
                    </a>
                    
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
                    
                    <a href = "<?= inner_url('user_lounch_fee/delete/') ?>?row_id=<?= $fee_lounch['id'] ?>" title="בטל">בטל</a>
                    
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
