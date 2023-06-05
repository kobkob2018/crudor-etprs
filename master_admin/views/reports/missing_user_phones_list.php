<h3>מספרי טלפון חסרים</h3>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">מספר</div>
        <div class="col">שיחה אחרונה</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($info as $missing_phone): ?>
        <div class="table-tr row">
            <div class="col">
                <?= $missing_phone['phone'] ?>
            </div>
            <div class="col">
                <?= hebdt($missing_phone['last_call']) ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('missing_user_phones/delete/') ?>?row_id=<?= $missing_phone['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

