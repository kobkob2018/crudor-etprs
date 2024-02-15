
<div class="apitoui hidden">
    <div class="items-table-template">
        <div class="table-tr row conversation-template">
            <div class="col item-id-col"></div>
            <div class="col msg-time-col"></div>
            <div class="col contact_phone_wa_id-col"></div>
            <div class="col contact_wa_name-col">
                
            </div>
            <div class="col">
                <span class="contact_custom_name-col">

                </span>
                <br/>
                <a href = "#" class="edit-href-col" title="ערוך איש קשר">[ערוך]</a>
            </div>
            <div class="col"><b class="last-message-direction-col"></b><br/></span>

                <br/><br/>
                <a href = "<?= inner_url('whatsapp_messages/add/') ?>?conversation_id=<?= $item['id'] ?>" title="לשיחה">לשיחה</a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('whatsapp_conversations/delete/') ?>?row_id=<?= $item['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    </div>
</div>