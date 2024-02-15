<?php foreach($this->data['whatsapp_messages'] as $item): ?>
    <div class="table-tr row message_tr" data-message_time = "<?= $item['message_time'] ?>" data-message_id="<?= $item['id'] ?>">
        <div class="col"><?= $item['id'] ?></div>
        <div class="col">
            <div class="<?= $item['direction'] ?> message-direction">
                <b><?= $item['message_type'] ?></b><br/>
                <?= $item['message_text'] ?>
            </div>

        </div>
        <div class="col">
            <a href = "<?= inner_url('whatsapp_messages/delete/') ?>?conversation_id=<?= $item['conversation_id'] ?>row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
        </div>
    </div>
<?php endforeach; ?>