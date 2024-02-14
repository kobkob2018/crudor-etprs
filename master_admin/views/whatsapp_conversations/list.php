<h3>שיחות ווטסאפ</h3>

<div class="add-button-wrap">
    לא ניתן ליזום שיחה. שיחות מאותחלות על ידי הפונים
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">#</div>
        <div class="col"></div>
        <div class="col">
            עדכון שיחה
        </div>
        <div class="col">שם ווטסאפ</div>
        <div class="col">שם הפונה</div>
        <div class="col">הודעה אחרונה</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['whatsapp_conversations'] as $item): ?>
        <div class="table-tr row">
            <div class="col"><?= $item['id'] ?></div>
            <div class="col"><?= hebdt($item['last_message_time'],'H:i<br/>d-m-Y') ?></div>
            <div class="col"><?= $item['contact_phone_wa_id'] ?></div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_conversations/edit/') ?>?&row_id=<?= $item['id'] ?>" title="ערוך באנר"><?= $item['contact_wa_name'] ?></a>
            </div>
            <div class="col"><?= $item['contact_custom_name'] ?></div>
            <div class="col"><b><?= $item['last_message']['direction'] ?></b><br/><?= $item['last_message']['message_type'] ?>: <?= $item['last_message']['message_text'] ?></div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_conversations/delete/') ?>?row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

