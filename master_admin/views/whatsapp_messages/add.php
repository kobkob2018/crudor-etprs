
<h3>שיחת ווטסאפ עם <?= $this->data['whatsapp_conversation']['contact_wa_name'] ?> (<?= $this->data['whatsapp_conversation']['contact_custom_name'] ?>)</h3>
<div class="messages-table-wrap open toggled-container" data-viewstate="open">
    <div class="table-header">
        <div class="messages-collapce-wrap">
            <a class="messages-colapce" href="javascript://" onclick="toggle_messages(this)">
                <span class="sign-when-open">🔼 סגור</span>
                <span class="sign-when-closed">הצג הודעות</span>
            </a>
        </div>
        <h2>הודעות</h2>
        <div class="clear"></div>
    </div>
    <div class="items-table flex-table messages-table" data-last_err="<?= $this->data['last_err'] ?>">
        <div class="table-th row messages-th">
            <div class="col">#</div>
            <div class="col">הודעה</div>
            <div class="col">מחיקה</div>
        </div>
        <?php foreach($this->data['whatsapp_messages'] as $item): ?>
            <div class="table-tr row message_tr <?= $item['send_state'] ?> message_<?= $item['id'] ?>" data-message_time = "<?= $item['message_time'] ?>" data-message_id="<?= $item['id'] ?>">
                <div class="col"><?= $item['id'] ?></div>
                <div class="col message-info">
                    <div class="<?= $item['direction'] ?> message-direction">
                        <?php if($item['image_link'] != ''): ?>
                            <div class="message_image">
                                <img src="<?= $item['image_link'] ?>" alt="the image link"/>
                            </div>
                        <?php endif; ?>
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
    </div>
</div>
<div class="hidden templates-list-wrap">
    <h3>תבניות ווטסאפ לשליחה מהירה</h3>
    <div class="templates-list">
        <?php foreach($this->data['templates'] as $template): ?>
            <div class="template-button" onclick="select_template(this)" data-template_id="<?= $template['id'] ?>">
                <?= $template['label'] ?>
            </div>
        <?php endforeach; ?>
    </div>
    <hr/>
    <div class="template-list-buttons">
        <a class="cancel-button" href="javascript://" onclick="close_template_list()">ביטול</a>
        <a class="load-button" href="javascript://" onclick="load_selected_template()">טען תבנית</a>
    </div>
</div>

<div class="focus-box">
    <div class="eject-box">
        <a href="<?= inner_url("whatsapp_conversations/list/") ?>">חזרה לרשימת השיחות</a>
    </div>
    <h3>שלח הודעה</h3>
    <hr/>
    <a class="upload-template-a" href="javascript://" onclick="open_templates_list()">
        לחץ כאן לשליחת תבנית מוכנה
    </a>
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
    a.messages-colapce{
        font-size: 18px;
    }
    .open .messages-colapce .sign-when-closed,
    .closed .messages-colapce .sign-when-open{
        display: none;
    }
    .message_tr.error{
        background: #feacac;
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
    
    .image-place-holder img{
        max-width:200px;
    }
    .message_image img{max-width: 100px;}
    .template-button{
        padding: 10px;
        background: #bfbfe1;
        border: 2px solid gray;
        border-radius: 4px;
        margin-bottom: 3px;
    }
    .template-button.selected{
        background: #36368b;
        color:white;
    }
    .templates-list-wrap{

        position: absolute;
        background: #ddcbcb;
        padding: 11px 13px;
        border-radius: 5px;
        border: 3px solid gray;
        box-shadow: 5px 5px 5px gray;
        top: 10px;
    }
    .template-list-buttons{
        display: flex;
        justify-content: space-between;
        padding: 5px;
    }
    .template-list-buttons a, a.upload-template-a{
        display: inline-block;
        padding: 10px;
        background: #8a8ac5;
        color: black;
        border-radius: 5px;
        border: 2px solid gray;
        font-family: Arial, Helvetica;
        font-weight: bold;
    }
    .template-list-buttons a.cancel-button{
        background: #ea9999;
    }
</style>

<div class="new-messages-placeholder hidden">
    
</div>
<div class="teplate-load-placeholder hidden">
    
</div>
<script type="text/javascript">

    function add_bot_options_to_form(){
        const form_container = document.querySelector(".send-form");
        const form_addition = document.querySelector(".form-addition");
        form_container.append(form_addition);
    }
    function init_whatsapp_fetch_messages(){
        //console.log("init_whatsapp_fetch_messages");
        setInterval(function(){fetch_whatsapp_messages()},10000);
    }

    function fetch_whatsapp_messages(){
        //console.log("fetch_whatsapp_messages");
        const messages_table = document.querySelector(".messages-table");  
        const last_err = messages_table.dataset.last_err;     
        const last_row = messages_table.querySelector(".message_tr");
        const last_message_id = last_row.dataset.message_id;
        const fetch_url = "<?= inner_url("whatsapp_messages/ajax_list/?conversation_id=".$this->data['whatsapp_conversation']['id']."&last_message_id=") ?>"+last_message_id+"&last_err="+last_err;
        const placeholder = document.querySelector(".new-messages-placeholder");
        const messages_th = messages_table.querySelector(".messages-th");
        //console.log(fetch_url);
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
            return update_err_messages(placeholder,messages_table);
            
        }
        const message_id = message_tr.dataset.message_id;
        messages_th.after(message_tr);
        move_rows_from_placeholder_to_table(placeholder,messages_table,messages_th);
    }

    function update_err_messages(placeholder,messages_table){
        const error_el = placeholder.querySelector(".ajax_err_msg");
        if(!error_el){
            return update_info_from_placeholder(placeholder,messages_table);
            
        }
        const error_msg = error_el.innerHTML;
        alert(error_msg);
        const msg_id = error_el.dataset.msg_id;
        const message_el = messages_table.querySelector(".message_"+msg_id);
        const message_info = message_el.querySelector(".message-info");
        message_el.classList.remove("send");
        message_el.classList.add("error");
        message_info.append(error_el);
        
        
        update_err_messages(placeholder,messages_table);
    }

    function update_info_from_placeholder(placeholder,messages_table){
        const info_holder = placeholder.querySelector(".info-holder");
        if(!info_holder){
            return;
        }
        const last_err = info_holder.dataset.last_err;
        messages_table.dataset.last_err = last_err;
    }

    function toggle_messages(a_el){
        const a_con = a_el.closest(".toggled-container");
        if(a_con.dataset.viewstate == 'open'){
            a_con.classList.remove('open');
            a_con.classList.add('closed');
            a_con.dataset.viewstate = "closed";
        }
        else{
            a_con.classList.add("open");
            a_con.classList.remove("closed");
            a_con.dataset.viewstate = "open";
        }
    }

    function init_image_pholder(){

        const image_pholder = document.createElement('div');
        const image_form_group = document.querySelector(".image-form-group");
        const image_form_group_wrap = image_form_group.querySelector(".form-group-en");
        image_form_group_wrap.appendChild(image_pholder);
        image_pholder.classList.add('image-place-holder');
        const image_url_holder = image_form_group.querySelector(".form-input");
        image_url_holder.addEventListener('change',evt=>{
            placeImageByNewUrl(evt.target.value,image_pholder);
        });

    }

    function placeImageByNewUrl(url,image_pholder) {
        image_pholder.querySelectorAll("img").forEach(img=>{img.remove()});
        if(url == ''){
            return;
        }
        var image = new Image();
        image.onload = function() {
            if (this.width > 0) {
            //console.log("image exists");
                image_pholder.append(image);
            }
        }
        image.onerror = function() {
            image.remove();
            // console.log("image doesn't exist");
        }
        image.src = url;
    }

    function init_video_pholder(){

        const video_pholder = document.createElement('div');
        const video_form_group = document.querySelector(".video-form-group");
        const video_form_group_wrap = video_form_group.querySelector(".form-group-en");
        video_form_group_wrap.appendChild(video_pholder);
        video_pholder.classList.add('video-place-holder');
        const video_url_holder = video_form_group.querySelector(".form-input");
        video_url_holder.addEventListener('change',evt=>{
            placeVideoByNewUrl(evt.target.value,video_pholder);
        });

    }

    function placeVideoByNewUrl(url,video_pholder) {

        video_pholder.querySelectorAll(".video").forEach(video=>{video.remove()});

        if(url == ''){
            return;
        }
        var video = document.createElement('video');
        video.onload = function() {
            if (this.width > 0) {
            //console.log("image exists");
                video_pholder.append(video);
            }
        }
        video.onerror = function() {
            video.remove();
            alert("no good video");
            // console.log("image doesn't exist");
        }
        video.src = url;
        video.autoplay = true;
    }

    function close_template_list(){
        document.querySelectorAll(".template-button").forEach(button=>{
            button.classList.remove("selected");
        });
        document.querySelector(".templates-list-wrap").classList.add("hidden");
    }
    function open_templates_list(){
        document.querySelector(".templates-list-wrap").classList.remove("hidden");
    }
    function select_template(selected_button){
        document.querySelectorAll(".template-button").forEach(button=>{
            button.classList.remove("selected");
        });
        selected_button.classList.add("selected");
    }
    function load_selected_template(){
        const selected_template_button = document.querySelector('.template-button.selected');
        if(!selected_template_button){
            alert("יש לבחור תבנית");
            return;
        }
        const template_id = selected_template_button.dataset.template_id;
        alert("loading template "+ template_id);
        const fetch_url = "<?= inner_url("whatsapp_templates/ajax_fetch/?template_id=") ?>"+template_id;
        fetch(fetch_url).then((res) => res.json()).then(info => {

            
            const image_input = document.querySelector(".image-form-group .form-input");
            const video_input = document.querySelector(".video-form-group .form-input");
            const text_input = document.querySelector(".text-form-group .form-input");
            const placeholder = document.querySelector(".teplate-load-placeholder");
            placeholder.innerHTML = info.html;

            const img_info_holder = placeholder.querySelector('.image-info-holder');
            if(img_info_holder){
                const img_url = img_info_holder.dataset.image_url;
                image_input.value = img_url;
                
            }
            const event = new Event('change');
            image_input.dispatchEvent(event);
            const video_info_holder = placeholder.querySelector('.video-info-holder');
            if(video_info_holder){
                const video_url = video_info_holder.dataset.video_url;
                video_input.value = video_url;
            }
            const video_event = new Event('change');
            video_input.dispatchEvent(video_event);
            const text_info_holder = placeholder.querySelector('.text-info-holder');
            if(!text_info_holder){
                alert("error accured try again later");
                return;
            }
            const text = text_info_holder.innerHTML;
            text_input.innerHTML = text;
            placeholder.innerHTML = "";
            init_image_pholder();
        }).catch(function(err) {
            console.log(err);
            alert("Something went wrong. please reload the page");
        });
        close_template_list();
        return;
    }

    add_bot_options_to_form();
    init_whatsapp_fetch_messages();
    init_image_pholder();
    init_video_pholder();
</script>