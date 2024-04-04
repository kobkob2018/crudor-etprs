<?php foreach($this->data['whatsapp_messages'] as $item): ?>
    <div class="table-tr row message_tr <?= $item['send_state'] ?>" data-message_time = "<?= $item['message_time'] ?>" data-message_id="<?= $item['id'] ?>">
        <div class="col"><?= $item['id'] ?></div>
        <div class="col">
            <div class="<?= $item['direction'] ?> message-direction ">
                <b><?= $item['message_type'] ?></b><br/>
                <?= $item['message_text'] ?>
            </div>
            <?php if($item['error_msg'] != ''): ?>
                <div class = "red err-msg">
                    <b>שגיאה בשליחת המסר: </b><?= $item['error_msg'] ?>
                </div>
            <?php endif; ?>
            <?php if(isset($item['context']) && $item['context'] != '0'): ?>
                <div class="message-context message-context-pending" data-context="<?= $item['context'] ?>">[-- <?= $item['context'] ?> --]</div>
            <?php endif; ?>
        </div>
        <div class="col">
            <a href = "<?= inner_url('whatsapp_messages/delete/') ?>?conversation_id=<?= $item['conversation_id'] ?>row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
        </div>
    </div>
<?php endforeach; ?>

<?php foreach($this->data['messages_errors'] as $err): ?>
    <div class="ajax_err_msg" data-msg_id="<?= $err['message_id'] ?>" data-err_msg = "<?= $err['error_msg'] ?>">
        התקבלה הודעת שגיאה: 
        <?= $err['error_msg'] ?>
    </div>
<?php endforeach; ?>
<div class="info-holder hidden" data-last_err = "<?= $this->data['last_err'] ?>"></div>