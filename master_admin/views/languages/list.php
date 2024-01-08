<?php $this->include_view("languages/header.php",$info); ?>
<h2>ניהול שפות</h2>
    <h4>הוספת שפה</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">שם השפה</div>
            <div class="col">קוד השפה</div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="createSend" />
            <input type="hidden" name="db_row_id" value="new" />

            <div class="col">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            </div>
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[iso_code]' value = "<?= $this->get_form_input('iso_code') ?>" />
            </div>
            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>

<h4>רשימת שפות</h4>


<?php if(!empty($this->data['language_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">שם השפה</div>
            <div class="col">קוד השפה</div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['language_list'] as $language): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $language['id'] ?>" /> 
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$language['form_identifier']) ?>" />
                    <br/>
                    <a href = "<?= inner_url('language_messages/list/') ?>?language_id=<?= $language['id'] ?>" title="בחירה">תרגומים ל<?= $language['label'] ?></a>
                
                </div>
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[iso_code]' value = "<?= $this->get_form_input('iso_code',$language['form_identifier']) ?>" />
                </div>

                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= $this->delete_url($language) ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין שפות</h4>
<?php endif; ?>