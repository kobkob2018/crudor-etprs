<?php foreach($this->data['whatsapp_conversations'] as $item): ?>
    <div class="table-tr row conversation_tr conversation-<?= $item['id'] ?>" data-last_message="<?= $item['last_message_time'] ?>">
        <div class="col"><?= $item['id'] ?></div>
        <div class="col"><?= hebdt($item['last_message_time'],'d-m-Y') ?><br/><?= hebdt($item['last_message_time'],'H:i') ?></div>
        <div class="col"><?= $item['contact_phone_wa_id'] ?></div>
        <div class="col">
            <?= $item['contact_wa_name'] ?>
        </div>
        <div class="col">
            <?= $item['contact_custom_name'] ?>
            <br/>
            <a href = "<?= inner_url('whatsapp_conversations/edit/') ?>?&row_id=<?= $item['id'] ?>" title="ערוך איש קשר">[ערוך]</a>
        </div>
        <div class="col"><b><?= $item['last_message']['direction'] ?></b><br/><?= $item['last_message']['message_type'] ?>: <?= $item['last_message']['message_text'] ?>

            <br/><br/>
            <a href = "<?= inner_url('whatsapp_messages/add/') ?>?conversation_id=<?= $item['id'] ?>" title="לשיחה">לשיחה</a>
        </div>
        <div class="col">
            <a href = "<?= inner_url('whatsapp_conversations/delete/') ?>?row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
        </div>
    </div>
<?php endforeach; ?>