

<h3>הודעות למשתמשים</h3>


<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('net_messages/add/') ?>">הוספת הודעה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון</div>
        <div class="col">סטטוס</div>
        <div class="col">X</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['net_messages'] as $net_message): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('net_messages/edit/') ?>?&row_id=<?= $net_message['id'] ?>" title="ערוך הודעה"><?= $net_message['label'] ?></a>
            </div>
            <div class="col">
                <?= $this->get_label_value('status',$net_message) ?>
            </div>
            <div class="col">
                <?= $net_message['send_count'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('net_messages/delete/') ?>?&row_id=<?= $net_message['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

