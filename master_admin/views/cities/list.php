<?php $this->include_view("cities/city_header.php"); ?>

<?php $this->include_view("crud/item_move_block.php"); ?>


<?php if(isset($this->data['move_item'])): ?>
    <div class="move-item-button-wrap">
        <a class='go-button' href="<?= inner_url('cities/list/') ?>?row_id=<?= $this->data['current_item_id'] ?>&move_item=here">העבר לכאן</a>
    </div>
<?php else: ?>
    <h4>הוספת עיר</h4>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col  col-first col-tiny">מיקום</div>
            <div class="col">שם</div>
            <div class="col">סטטוס</div>
            <div class="col">סוג</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "<?= inner_url("cities/add/") ?>?row_id=<?= $this->data['current_item_id'] ?>" method = "POST" >
            <input type="hidden" name="sendAction" value="createSend" />
            <input type="hidden" name="db_row_id" value="new" />
            <div class="col col-first col-tiny">                   
                <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority') ?>" />
            </div>
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            </div>

            <div class="col">
                <select name='row[active]' class='table-select'>
                    <?php foreach($this->get_select_options('active') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <select name='row[type]' class='table-select'>
                    <?php foreach($this->get_select_options('type') as $option): ?>
                        <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>





<?PHP endif; ?>

<h4>רשימת הערים באזור</h4>


<?php if(!empty($this->data['city_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col  col-first col-tiny">מיקום</div>
            <div class="col">פעיל</div>
            <div class="col">שם</div>
            <div class="col">סוג</div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['city_list'] as $city): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $city['id'] ?>" /> 
                <div class="col col-first col-tiny">
                    <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority',$city['form_identifier']) ?>" />
                </div>
                <div class="col">
                   <select name='row[active]' class='table-select'>
                       <?php foreach($this->get_select_options('active',$city['form_identifier']) as $option): ?>
                           <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                       <?php endforeach; ?>
                   </select>
               </div>
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$city['form_identifier']) ?>" />
                    <br/>
                    <a href = "<?= inner_url('cities/list/') ?>?row_id=<?= $city['id'] ?>" title="בחירה">תתי ערים</a>
                
                </div>

                <div class="col">
                   
                    <select name='row[type]' class='table-select'>
                        <?php foreach($this->get_select_options('type',$city['form_identifier']) as $option): ?>
                            <option value="<?= $option['value'] ?>" <?= $option['selected'] ?>><?= $option['title'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="col">

                    <?php if(isset($this->data['move_item'])): ?>
                        <a class='go-button' href="<?= inner_url('cities/list/') ?>?row_id=<?= $city['id'] ?>&move_item=here">העבר לכאן</a>
                    <?php else: ?>

                        <a href = "<?= inner_url('cities/list/') ?>?move_item=<?= $city['id'] ?>" title="העברה">העברה</a>
                    <?PHP endif; ?>
                
                
                </div>
                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('cities/delete/') ?>?row_id=<?= $city['id'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין תתי ערים</h4>
<?php endif; ?>