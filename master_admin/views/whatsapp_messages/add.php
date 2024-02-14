
<h3>שיחת ווטסאפ עם <?= $this->data['whatsapp_conversation']['contact_wa_name'] ?> (<?= $this->data['whatsapp_conversation']['contact_custom_name'] ?>)</h3>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">#</div>
        <div class="col">הודעה</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['whatsapp_messages'] as $item): ?>
        <div class="table-tr row">
            <div class="col"><?= $item['id'] ?></div>
            <div class="col">
                <div class="<?= $item['direction'] ?>">
                    <b><?= $item['message_type'] ?></b><br/>
                    <?= $item['message_text'] ?>
                </div>

            </div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_messages/delete/') ?>?conversation_id=<?= $item['conversation_id'] ?>row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("whatsapp_conversations/list/") ?>">חזרה לרשימת השיחות</a>
    </div>
    <h3>שלח הודעה</h3>
    <hr/>
    <div id="block_form_wrap" class="form-gen page-form">
        <?php $this->include_view('form_builder/form.php'); ?>
    </div>
</div>
