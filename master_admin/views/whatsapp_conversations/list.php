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
</div>

<div class="new-conversations-placeholder hidden" style="display:block; background:blue; padding:10px;">

</div>

<a href="javascript://" onclick="fetch_whatsapp_conversations()" >
        <h2>check it out!!</h2>
</a>

<script type="text/javascript">


    function init_whatsapp_fetch_conversations(){

        

    }

    function fetch_whatsapp_conversations(){
        const conversations_table = document.querySelector(".conversations-table");       
        const last_row = conversations_table.querySelector(".conversation_tr");
        const last_message_time = last_row.dataset.last_message;
        const fetch_url = "<?= inner_url("whatsapp_conversations/ajax_list/?last_message_time=") ?>"+last_message_time;
        const placeholder = document.querySelector(".new-conversations-placeholder");
        console.log(fetch_url);
        fetch(fetch_url).then((res) => res.json()).then(info => {
            placeholder.append(info.conversations_html);
        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
    }


</script>