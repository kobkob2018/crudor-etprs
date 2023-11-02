<h2>הפניות 301 באתר</h2>
    
    <h4>הוספת הפנייה</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col big-text">שם לזיהוי</div>
            <div class="col">סוג הדף (פרמטר ראשי - m)</div>
            <div class="col">שם הפרמטר המזהה</div>
            <div class="col">מזהה הפריט</div>
            <div class="col">כתובת הפנייה</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="listCreateSend" />
            <input type="hidden" name="db_row_id" value="new" />

            <div class="col big-text">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            </div>

            <div class="col">
                <select name='row[m_param]' class='table-select'>
                    <?php foreach($this->get_select_options('m_param') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name='row[id_param]' class='table-select'>
                    <?php foreach($this->get_select_options('id_param') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <input type="text" class = 'table-input' name = 'row[item_id]' value = "<?= $this->get_form_input('item_id') ?>" />
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
            <div class="col">סוג הדף (פרמטר ראשי - m)</div>
            <div class="col">שם הפרמטר המזהה</div>
            <div class="col">מזהה הפריט</div>
            <div class="col">כתובת הפנייה</div>
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
                   <select name='row[m_param]' class='table-select'>
                       <?php foreach($this->get_select_options('m_param',$item['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>

               <div class="col">
                   <select name='row[id_param]' class='table-select'>
                       <?php foreach($this->get_select_options('id_param',$item['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>



                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[item_id]' value = "<?= $this->get_form_input('item_id',$item['form_identifier']) ?>" />
                </div>

                
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url',$item['form_identifier']) ?>" />
                </div>

                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('redirections/delete/') ?>?row_id=<?= $item['id'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין הפניות</h4>
<?php endif; ?>