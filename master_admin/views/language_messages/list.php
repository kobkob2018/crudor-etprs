<?php $this->include_view("languages/header.php",$info); ?>
    <h3>תרגומים לשפה: <?= $this->data['current_language']['label'] ?></h3>
    <div class="eject-box">
        <a class="back-link" href="<?= inner_url('languages/list/?system_id='.$info['system_id']) ?>">חזרה לרשימת השפות במערכת <?= $info['system_id_label'] ?></a>
    </div>
    <h4>הוספת תרגום</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col big-text">משפט מקור</div>
            <div class="col big-text">תרגום לשפה: <?= $this->data['current_language']['label'] ?></div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="createSend" />
            <input type="hidden" name="db_row_id" value="new" />

            <div class="col big-text">
                <input type="text" class = 'table-input' name = 'row[msgid]' value = "<?= $this->get_form_input('msgid') ?>" />
            </div>
            <div class="col big-text">
                <input type="text" class = 'table-input' name = 'row[msgstr]' value = "<?= $this->get_form_input('msgstr') ?>" />
            </div>
            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>

<h4>רשימת שפות</h4>


<?php if(!empty($this->data['message_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col big-text">משפט מקור</div>
            <div class="col big-text">תרגום לשפה: <?= $this->data['current_language']['label'] ?></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['message_list'] as $message): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $message['id'] ?>" /> 
                <div class="col big-text">
                    <input type="text" class = 'table-input' name = 'row[msgid]' value = "<?= $this->get_form_input('msgid',$message['form_identifier']) ?>" />
                </div>
                <div class="col big-text">
                    <input type="text" class = 'table-input' name = 'row[msgstr]' value = "<?= $this->get_form_input('msgstr',$message['form_identifier']) ?>" />
                </div>

                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= $this->delete_url($message) ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין תרגומים לשפה זו</h4>
<?php endif; ?>