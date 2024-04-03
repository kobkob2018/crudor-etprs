
<h3>שיחת ווטסאפ עם <?= $this->data['whatsapp_conversation']['contact_wa_name'] ?> (<?= $this->data['whatsapp_conversation']['contact_custom_name'] ?>)</h3>
<div class="messages-table-wrap open toggled-container" data-viewstate="open">
    <div class="messages-collapce-wrap">
        <a class="messages-colapce" href="javascript://" onclick="toggle_messages(this)">
            <span class="sign-when-open">^^^^</span>
            <span class="sign-when-closed">++++</span>
        </a>
    </div>
    <h2>הודעות</h2>
    <div class="items-table flex-table messages-table">
        <div class="table-th row messages-th">
            <div class="col">#</div>
            <div class="col">הודעה</div>
            <div class="col">מחיקה</div>
        </div>
        <?php foreach($this->data['whatsapp_messages'] as $item): ?>
            <div class="table-tr row message_tr" data-message_time = "<?= $item['message_time'] ?>" data-message_id="<?= $item['id'] ?>">
                <div class="col"><?= $item['id'] ?></div>
                <div class="col">
                    <div class="<?= $item['direction'] ?> message-direction">
                        <b><?= $item['message_type'] ?></b><br/>
                        <?= $item['message_text'] ?>
                    </div>
                    <?php if(isset($item['context']) && $item['context'] != '0'): ?>
                        <div class="message-context message-context-pending" data-context="<?= $item['context'] ?>">[-- <?= $item['context'] ?> --]</div>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <a href = "<?= inner_url('whatsapp_messages/delete/') ?>?conversation_id=<?= $item['conversation_id'] ?>row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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


<div class="form-addition">
    <div class="focus-box sub-focus">
        <h3>מצב הפרעה לשיחה</h3>
        <b>שים לב. במידה ואתה שולח הודעה יזומה, כאשר הפונה מהצד השני מגיב, נשלחות הודעות אוטומטיות מהמערכת.</b>
        <br/>
        באפשרותך לבחור אם להמשיך את תגובות המערכת האוטומטיות או להמשיכן, ובוסף, אם להמשיך לאסוף מידע.
        <div  class="form-group">
            <input type="checkbox" class="input-checkbox" name='auto_reply' value="1" <?= $this->data['bot_state_checkboxes']['auto_reply']['checked'] ?>/>שלח תגובות מערכת בצורה אוטומטית
        </div>
        <div  class="form-group">
            <input type="checkbox" class="input-checkbox" name='info_collect' value="1" <?= $this->data['bot_state_checkboxes']['info_collect']['checked'] ?>/>אסוף מידע לאחר הודעות נכנסות
        </div>
        <div  class="form-group">
            <input type="checkbox" class="input-checkbox" name='admin_alerts' value="1" <?= $this->data['bot_state_checkboxes']['admin_alerts']['checked'] ?>/>שלח התראות למנהל על כל הודעה נכנסת
        </div>
        

    </div>
</div>

<style type="text/css">

    .form-addition .input-checkbox{
        width: 20px;
        min-width: 40px;
        height: 20px;
        margin-bottom: 9px;
    }
    .items-table{
        height: 260px;
        margin-bottom: 20px;
        overflow: auto;

    }

    .toggled-container.closed{
        height: 50px;
    }

    .messages-table-wrap{
        background: #ddf0e1;
        border-radius: 5px;
        padding: 5px;
        box-shadow: 5px 5px 5px gray;
        margin-top: 38px;
        border: 4px outset #54e674;
        overflow: hidden;
    }
    .messages-collapce-wrap{
        float: left;
    }
    .open .messages-colapce .sign-when-closed,
    .closed .messages-colapce .sign-when-open{
        display: none;
    }
    @media only screen and (min-width: 1000px) {
        .messages-table-wrap{
            width: 50%;
            float: left;
        }
    }
    .message-direction{
        background: #9191ff;
        padding: 10px 15px 10px 30px;
        border-radius: 5px;
    }
    .message-direction.send{
        float: left;
        margin-right: 20px;
        background: yellow;
    }
    .message-direction.recive{
        float: right;
        margin-left: 20px;
    }


</style>

<div class="new-messages-placeholder hidden">
    
</div>

<script type="text/javascript">

    function add_bot_options_to_form(){
        const form_container = document.querySelector(".send-form");
        const form_addition = document.querySelector(".form-addition");
        form_container.append(form_addition);
    }
    function init_whatsapp_fetch_messages(){
        console.log("init_whatsapp_fetch_messages");
        setInterval(function(){fetch_whatsapp_messages()},10000);
    }

    function fetch_whatsapp_messages(){
        console.log("fetch_whatsapp_messages");
        const messages_table = document.querySelector(".messages-table");       
        const last_row = messages_table.querySelector(".message_tr");
        const last_message_id = last_row.dataset.message_id;
        const fetch_url = "<?= inner_url("whatsapp_messages/ajax_list/?conversation_id=".$this->data['whatsapp_conversation']['id']."&last_message_id=") ?>"+last_message_id;
        const placeholder = document.querySelector(".new-messages-placeholder");
        const messages_th = messages_table.querySelector(".messages-th");
        console.log(fetch_url);
        fetch(fetch_url).then((res) => res.json()).then(info => {
            placeholder.innerHTML = info.messages_html;

            move_rows_from_placeholder_to_table(placeholder,messages_table,messages_th);


        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
    }

    function move_rows_from_placeholder_to_table(placeholder,messages_table,messages_th){
        const message_tr = placeholder.querySelector(".message_tr");
        if(!message_tr){
            return;
        }
        const message_id = message_tr.dataset.message_id;
        messages_th.after(message_tr);
        move_rows_from_placeholder_to_table(placeholder,messages_table,messages_th);
    }

    function toggle_messages(a_el){
        const a_con = a_el.closest(".toggled-container");
        if(a_con.dataset.state == 'open'){
            a_con.classList.remove('open');
            a_con.classList.add('closed');
            a_con.dataset.state = "closed";
        }
        else{
            a_con.classList.add("open");
            a_con.classList.remove("closed");
            a_con.dataset.state = "open";
        }
    }

    add_bot_options_to_form();
    init_whatsapp_fetch_messages();
</script>