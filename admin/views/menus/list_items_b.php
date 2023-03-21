<h2><?= $this->data['page_title'] ?></h2>
<?php if(isset($this->data['move_menu_item'])): ?>
    <div class = 'move-item-wrap alt-box'>
        <h3>העברת קישור
        <br/>
        <?php foreach($this->data['move_menu_item']['tree'] as $parent_item): ?>
            <?php if($parent_item['is_current']): ?>
                <?= $parent_item['title'] ?>
            <?php else: ?>
                <a href = "<?= $this->data['action_url'] ?>?item_id=<?= $parent_item['id'] ?>">
                    <?= $parent_item['title'] ?>
                </a>
                 > 
            <?php endif; ?>
        <?php endforeach; ?>    
        </h3>
        <p>בחר את קישור האב ולחץ "העבר לכאן"</p>
        <div class='bottom-buttons'>
                <div class='cancel-button-wrap'>
                    <a class='cancel-button' href="<?= current_url(array('move_item'=>'cancel')) ?>">ביטול</a> 
                </div>
        </div>

    </div>
<?php endif; ?>
<?php if($this->data['main_menu_item']): ?>
    <h3>רשימת תתי תפריט לקישור
        <br/>
        <a href = "<?= $this->data['action_url'] ?>">
            ראשי
        </a>
         > 
        <?php foreach($this->data['item_parent_tree'] as $parent_item): ?>
            <?php if($parent_item['is_current']): ?>
                <?= $parent_item['title'] ?>
            <?php else: ?>
                <a href = "<?= $this->data['action_url'] ?>?item_id=<?= $parent_item['id'] ?>">
                    <?= $parent_item['title'] ?>
                </a>
                 > 
            <?php endif; ?>
        <?php endforeach; ?>

    </h3>
<?php else: ?>
    <h3>קישורים ראשיים</h3>
<?php endif; ?>
<?php $this->include_view('messages/formMessages.php'); ?>
<h4>הוספת קישור חדש</h4>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-first col-tiny">מיקום</div>
        <div class="col">תווית</div>
        <div class="col">סוג הקישור</div>
        <div class="col">כתובת הקישור</div>
        <div class="col">קישור לדף</div>
        <div class="col">ייפתח ב</div>
        <div class="col col-small">תווית עיצוב</div>
        <div class="col"></div>
    </div>

    
    <form  class="table-tr row" action = "" method = "POST" >
        <input type="hidden" name="sendAction" value="createSend" />
        <?php if(isset($this->data['item_id'])): ?>
            <input type="hidden" name="row[parent]" value="<?= $this->data['item_id'] ?>" />    
        <?php endif; ?>
        <input type="hidden" name="row[site_id]" value="<?= $this->data['site']['id'] ?>" />
        <input type="hidden" name="row[menu_id]" value="<?= $this->data['menu_id'] ?>" />
        <div class="col col-first col-tiny">                   
            <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority') ?>" />
        </div>
        <div class="col">
            <input type="text" class = 'table-input' name = 'row[title]' value = "<?= $this->get_form_input('title') ?>" />
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
                <option value="-1">--- בחר דף ---</option>
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
        <div class="col col-small">
            <input type="text" class = 'table-input' name = 'row[css_class]' value = "<?= $this->get_form_input('css_class') ?>" />
        </div>
        <div class="col"><input type="submit" value="שמור" /></div>

        
    </form>
   

</div>

<h4>קישורים קיימים</h4>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-first col-tiny">מיקום</div>
        <div class="col">תווית</div>
        <div class="col">סוג הקישור</div>
        <div class="col">כתובת הקישור</div>
        <div class="col">קישור לדף</div>
        <div class="col">ייפתח ב</div>
        <div class="col col-small">תווית עיצוב</div>
        <div class="col"></div>
        <div class="col"></div>
    </div>

    <?php foreach($this->data['menu_item_list'] as $menu_item): ?>
        <form  class="table-tr row" action = "" method = "POST" >
            <input type="hidden" name="sendAction" value="updateSend" />
            <input type="hidden" name="row_id" value="<?= $menu_item['id'] ?>" />    
            <div class="col col-first col-tiny">                   
                <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority',$menu_item['form_identifier']) ?>" />
            </div>
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[title]' value = "<?= $this->get_form_input('title',$menu_item['form_identifier']) ?>" />
                <br/>
                <a href="<?= $this->data['action_url'] ?>?item_id=<?= $menu_item['id'] ?>" title="מעבר לתתי קישורים">עריכה ותתי קישורים</a>
            </div>
            <div class="col">
                <select name='row[link_type]' class='table-select'>
                    <?php foreach($this->get_select_options('link_type',$menu_item['form_identifier']) as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[url]' value = "<?= $this->get_form_input('url',$menu_item['form_identifier']) ?>" />
            </div>
            <div class="col">
                <select name='row[page_id]' class='table-select'>
                    <option value="-1">--- בחר דף ---</option>
                    <?php foreach($this->get_select_options('page_id',$menu_item['form_identifier']) as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <select name='row[target]' class='table-select'>
                    <?php foreach($this->get_select_options('target',$menu_item['form_identifier']) as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br/>
                <?php if(isset($this->data['move_menu_item'])): ?>
                    <a class='go-button' href="<?= $this->data['action_url'] ?>?item_id=<?= $menu_item['id'] ?>&move_item=here">העבר לכאן</a>
                <?php else: ?>
                    <a href="<?= $this->data['action_url'] ?>?move_item=<?= $menu_item['id'] ?>" title="העבר קטגוריה">העבר קטגוריה</a>
                <?php endif; ?>
            </div>
            <div class="col col-small">
                <input type="text" class = 'table-input' name = 'row[css_class]' value = "<?= $this->get_form_input('css_class',$menu_item['form_identifier']) ?>" />
            </div>
            <div class="col"><input type="submit" value="שמור" /></div>
            <div class="col">
                <a class = 'delete-item-x' href="<?= current_url(array('delete'=>$menu_item['id'])) ?>">
                    X
                </a>
            </div>
           
        </form>
    <?php endforeach; ?>

</div>
        