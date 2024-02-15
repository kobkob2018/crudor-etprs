<h3>שיחות ווטסאפ</h3>

<div class="add-button-wrap">
    לא ניתן ליזום שיחה. שיחות מאותחלות על ידי הפונים
</div>

<div class="items-table flex-table conversations-table">
    <div class="table-th row conversations-th">
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
        <div class="table-tr row conversation_tr conversation-<?= $item['id'] ?>" data-last_message="<?= $item['last_message_time'] ?>"  data-conversation_id="<?= $item['id'] ?>">
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
</div>

<div class="new-conversations-placeholder hidden">

</div>

<script type="text/javascript">


    function init_whatsapp_fetch_conversations(){
        setInterval(function(){fetch_whatsapp_conversations()},80000);
    }

    function fetch_whatsapp_conversations(){
        const conversations_table = document.querySelector(".conversations-table");       
        const last_row = conversations_table.querySelector(".conversation_tr");
        const last_message_time = last_row.dataset.last_message;
        const fetch_url = "<?= inner_url("whatsapp_conversations/ajax_list/?last_message_time=") ?>"+last_message_time;
        const placeholder = document.querySelector(".new-conversations-placeholder");
        const conversations_th = conversations_table.querySelector(".conversations-th");
        console.log(fetch_url);
        fetch(fetch_url).then((res) => res.json()).then(info => {
            placeholder.innerHTML = info.conversations_html;

            move_rows_from_placeholder_to_table(placeholder,conversations_table,conversations_th);


        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
    }

    function move_rows_from_placeholder_to_table(placeholder,conversations_table,conversations_th){
        const conversation_tr = placeholder.querySelector(".conversation_tr");
        if(!conversation_tr){
            return;
        }
        const conversation_id = conversation_tr.dataset.conversation_id;
        const old_conversation_tr = conversations_table.querySelector(".conversation-"+conversation_id);
        old_conversation_tr.remove();
        conversations_th.after(conversation_tr);
        move_rows_from_placeholder_to_table(placeholder,conversations_table,conversations_th);
    }
    init_whatsapp_fetch_conversations();
</script>