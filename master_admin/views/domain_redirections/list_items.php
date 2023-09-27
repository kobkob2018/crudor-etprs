
<h2>הפניות 301 לדומיינים</h2>
    
    <h4>הוספת הפנייה</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col big-text">שם לזיהוי</div>
            <div class="col big-text">דומיין</div>
            <div class="col big-text">כתובת הפנייה</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="listCreateSend" />
            <input type="hidden" name="db_row_id" value="new" />

            <div class="col big-text">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            </div>


            <div class="col">
                <input type="text" class = 'table-input' name = 'row[domain]' value = "<?= $this->get_form_input('domain') ?>" />
            </div>

            <div class="col">
                <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url') ?>" />
            </div>

            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>







<h4>רשימת ההפניות באתר</h4>


<?php if(!empty($this->data['item_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col big-text">שם לזיהוי</div>
            <div class="col">דומיין</div>
            <div class="col big-text">כתובת הפנייה</div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['item_list'] as $item): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 

                <div class="col big-text">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                </div>

                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[domain]' value = "<?= $this->get_form_input('domain',$item['form_identifier']) ?>" />
                </div>

                
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url',$item['form_identifier']) ?>" />
                </div>

                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('domain_redirections/delete/') ?>?row_id=<?= $item['id'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין הפניות</h4>
<?php endif; ?>