<h2>הצעות המחיר שלי</h2>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">מיקום</div>
        <div class="col">כותרת</div>
        <div class="col">תאור</div>
        <div class="col">מחיר</div>
        <div class="col">טקסט למחיר</div>
        <div class="col"></div>
    </div>

    <form  class="table-tr row" action = "" method = "POST" >
        <input type="hidden" name="sendAction" value="listCreateSend" />
        <input type="hidden" name="db_row_id" value="new" />
        <div class="col col-tiny">
            <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority') ?>" />
        </div>
        <div class="col">
            <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
        </div>
        <div class="col col-top">
            <textarea class = 'table-input' name = 'row[description]'><?= $this->get_form_input('description') ?></textarea>
        </div>
        <div class="col">
            <input type="text" class = 'table-input' name = 'row[price]' value = "<?= $this->get_form_input('price') ?>" />
        </div>
        <div class="col col-top">
            <textarea class = 'table-input' name = 'row[price_text]'><?= $this->get_form_input('price_text') ?></textarea>
        </div>

        <div class="col"><input type="submit" value="שמור" /></div>
    </form>


</div>

<hr/>


<?php if(!empty($this->data['quote_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
        <div class="col col-tiny">מיקום</div>
            <div class="col">כותרת</div>
            <div class="col">תאור</div>
            <div class="col">מחיר</div>
            <div class="col">טקסט למחיר</div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['quote_list'] as $item): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 

                <div class="col col-tiny">
                    <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority',$item['form_identifier']) ?>" />
                </div>
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                </div>
                <div class="col col-top">
                    <textarea class = 'table-input' name = 'row[description]'><?= $this->get_form_input('description',$item['form_identifier']) ?></textarea>
                </div>
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[price]' value = "<?= $this->get_form_input('price',$item['form_identifier']) ?>" />
                </div>
                <div class="col col-top">
                    <textarea class = 'table-input' name = 'row[price_text]'><?= $this->get_form_input('price_text',$item['form_identifier']) ?></textarea>
                </div>


                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('quotes/delete/') ?>?row_id=<?= $item['id'] ?>&list_action=user_list">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין הפניות</h4>
<?php endif; ?>

