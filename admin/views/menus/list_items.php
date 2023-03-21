<?php $this->include_view("menus/menu_item_header.php"); ?>

<?php $this->include_view("crud/item_move_block.php"); ?>


<?php if(isset($this->data['move_item'])): ?>
    <div class="move-item-button-wrap"> 
        <a class='go-button' href="<?= inner_url('menus/'.$this->data['action_name'].'/') ?>?row_id=<?= $this->data['current_item_id'] ?>&move_item=here">העבר לכאן</a>
    </div>
<?php else: ?>
    <h4>הוספת עיר</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col  col-first col-tiny">מיקום</div>
            <div class="col">תווית</div>
            <div class="col">סוג הקישור</div>
            <div class="col">כתובת הקישור</div>
            <div class="col">קישור לדף</div>
            <div class="col">ייפתח ב</div>
            <div class="col col-small">תווית עיצוב</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="listCreateSend" />
            <input type="hidden" name="menu_identifier" value="<?= $this->data['menu_identifier'] ?>" />
            <input type="hidden" name="db_row_id" value="new" />
            <div class="col col-first col-tiny">                   
                <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority') ?>" />
            </div>
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            </div>

            <div class="col">
                <select name='row[link_type]' class='table-select'>
                    <?php foreach($this->get_select_options('link_type') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url') ?>" />
            </div>


            <div class="col">
                <select name='row[page_id]' class='table-select'>
                    <?php foreach($this->get_select_options('page_id') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name='row[target]' class='table-select'>
                    <?php foreach($this->get_select_options('target') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <input type="text" class = 'table-input' name = 'row[css_class]' value = "<?= $this->get_form_input('css_class') ?>" />
            </div>

            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>





<?PHP endif; ?>

<h4>רשימת הערים באזור</h4>


<?php if(!empty($this->data['item_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
        <div class="col  col-first col-tiny">מיקום</div>
            <div class="col">תווית</div>
            <div class="col">סוג הקישור</div>
            <div class="col">כתובת הקישור</div>
            <div class="col">קישור לדף</div>
            <div class="col">ייפתח ב</div>
            <div class="col col-small">תווית עיצוב</div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['item_list'] as $item): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 
                <div class="col col-first col-tiny">
                    <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority',$item['form_identifier']) ?>" />
                </div>

                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                    <br/>
                    <a href = "<?= inner_url('menus/'.$this->data['action_name'].'/') ?>?row_id=<?= $item['id'] ?>" title="בחירה">תתי תפריט</a>
                </div>

                <div class="col">
                   <select name='row[link_type]' class='table-select'>
                       <?php foreach($this->get_select_options('link_type',$item['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>



                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url',$item['form_identifier']) ?>" />
                        <br/>
                    <?php if(isset($this->data['move_item'])): ?>
                        <a class='go-button' href="<?= inner_url('menus/'.$this->data['action_name'].'/') ?>?row_id=<?= $item['id'] ?>&move_item=here">העבר לכאן</a>
                    <?php else: ?>

                        <a href = "<?= inner_url('menus/'.$this->data['action_name'].'/') ?>?move_item=<?= $item['id'] ?>" title="העברה">העברה</a>
                    <?PHP endif; ?>
                </div>


                <div class="col">
                   <select name='row[page_id]' class='table-select'>
                       <?php foreach($this->get_select_options('page_id',$item['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>

               <div class="col">
                   <select name='row[target]' class='table-select'>
                       <?php foreach($this->get_select_options('target',$item['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>

                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[css_class]' value = "<?= $this->get_form_input('css_class',$item['form_identifier']) ?>" />
                </div>
                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('menus/delete/') ?>?row_id=<?= $item['id'] ?>&menu_identifier=<?= $this->data['menu_identifier'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין תתי ערים</h4>
<?php endif; ?>