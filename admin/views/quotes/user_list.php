
<?php if(isset($this->data['user_info'])): ?>
    <div class="eject-box">
        <a href="<?= inner_url("quote_cats/list/") ?>">חזרה לרשימה ראשית</a>
    </div>
    <?php $this->include_view("quotes/user_header.php"); ?>
    <hr/>
    <h2>הצעות מחיר של לקוח: <?= $this->data['user_info']['full_name'] ?></h2>

    <?php $this->include_view('quotes/queue_options.php',array('enable_assign_user'=>true)); ?>
<?php else: ?>
    <h2>הצעות המחיר שלי</h2>
<?php endif; ?>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">מיקום</div>
        <div class="col">כותרת</div>
        <div class="col">תאור</div>
        <div class="col">מחיר</div>
        <div class="col">טקסט למחיר</div>
        <div class="col">טלפון</div>
        <div class="col">לינק</div>
        <div class="col">שיוך לרשימות</div>
        <div class="col"></div>
    </div>

    <form  class="table-tr row" action = "" method = "POST" enctype="multipart/form-data" >
        <input type="hidden" name="sendAction" value="listCreateSend" />
        <input type="hidden" name="db_row_id" value="new" />
        <input type="hidden" name="row[user_id]" value="<?= $info['list_user']['id'] ?>" />
        <div class="col col-tiny">
            <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority') ?>" />
        </div>
        <div class="col">
            <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
            <div class="sub-row">
                <b>תמונה\לוגו</b><br/>
                <input type="file" name="row[image]" id="row_image" accept="image/png, image/gif, image/jpeg, image/x-icon, image/svg+xml" class="form-input" value="" />
            </div>
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
        <div class="col col-top">
            <textarea class = 'table-input' name = 'row[phone]'><?= $this->get_form_input('phone') ?></textarea>
        </div>
        <div class="col col-top">
            <textarea class = 'table-input' name = 'row[link]'><?= $this->get_form_input('link') ?></textarea>
        </div>
        <div class="col cat-list-select-wrap">
            <div class="cat-list-finder-wrap">
                <input type="text" placeholder="חפש רשימה" class="list-select" onkeyup="list_quote_cat_options(this)" onfocus="list_quote_cat_options(this)"/>
                <div class="cat-list-wrap hidden">
                    <a class = 'close-list-x' href="javascript://" onclick="close_result_list(this)">
                        X
                    </a>
                
                    <div class="cat-list-results">
                        
                    </div>
                </div>

            </div>
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
            <div class="col">טלפון</div>
            <div class="col">לינק</div>
            <div class="col">שיוך לרשימות</div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['quote_list'] as $item): ?>
            <form  class="table-tr row" action = "" method = "POST" enctype="multipart/form-data">
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 
                <input type="hidden" name="assign[]" value="-1" /> 

                <div class="col col-tiny">
                    <input type="text" class = 'table-input' name = 'row[priority]' value = "<?= $this->get_form_input('priority',$item['form_identifier']) ?>" />
                </div>
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                    <div class="sub-row">
                        <b>תמונה\לוגו</b><br/>
                        <input type="file" name="row[image]" id="row_image" accept="image/png, image/gif, image/jpeg, image/x-icon, image/svg+xml" class="form-input" value="" />                      
                        <?php if($file_url = $this->get_form_file_url('image',$item['form_identifier'])): ?>
                        <div>
                            
                            <a href="<?= $file_url ?>" target="_BLANK">  
                                <img src='<?= $file_url ?>?cache=<?= rand() ?>'  style="max-width:200px;"/>
                            </a>
                            <br/>
                            <a href="<?= inner_url('quotes/edit/?cat_id=none&user_id='.$item['user_id'].'&row_id='.$item['id']) ?>&remove_file=image">הסר תמונה</a>
                        </div>
                        <?php endif; ?>
                    </div>
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
                <div class="col col-top">
                    <textarea class = 'table-input' name = 'row[phone]'><?= $this->get_form_input('phone',$item['form_identifier']) ?></textarea>
                </div>
                <div class="col col-top">
                    <textarea class = 'table-input' name = 'row[link]'><?= $this->get_form_input('link',$item['form_identifier']) ?></textarea>
                </div>
                <div class="col cat-list-select-wrap">
                    <div class="cat-list-finder-wrap">
                        <?php if($view->user_is('master_admin') && $item['status'] == '9'): ?>
                            <div>
                                <b class="red">הצעות המחיר בהשהייה</b>
                            </div>
                        <?php endif; ?>
                        <input type="text" placeholder="חפש רשימה" class="list-select" onkeyup="list_quote_cat_options(this)"  onfocus="list_quote_cat_options(this)"/>
                        <div class="cat-list-wrap hidden">
                            <a class = 'close-list-x' href="javascript://" onclick="close_result_list(this)">
                                X
                            </a>
                        
                            <div class="cat-list-results">
                                
                            </div>
                        </div>
                        <?php foreach($item['cats_assigned'] as $cat): ?>
                            <div class="cat-assign-template cat-assign-checkbox" data-cat_id="<?= $cat['id'] ?>">
                                <input class="assign-input" type="hidden" name="assign[]" value="<?= $cat['id'] ?>"  />
                                <a class="cat-remove-x" href="javascript://" onclick="remove_cat_assign(this)">X</a>
                                <span class="cat-label">
                                    <?= $cat['label'] ?>
                                </span> 
                                <?php if($view->user_is('master_admin')): ?>
                                    &nbsp;<a class="edit-cat-link" href="javascript://" onclick="nav_to_cat_edit(this)" title="ערוך תיקייה">[ערוך]</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
<div class="api-to-ui hidden">
    <div class="cat-result-template" onclick="select_cat_assign(this)">

    </div>
    <div class="cat-assign-template cat-assign-checkbox">
        <input class="assign-input" type="hidden" name="assign[]"  />
        <a class="cat-remove-x" href="javascript://" onclick="remove_cat_assign(this)">X</a>
        <span class="cat-label">

        </span>
        <?php if($view->user_is('master_admin')): ?>
            &nbsp;<a class="edit-cat-link" href="javascript://" onclick="nav_to_cat_edit(this)" title="ערוך תיקייה">[ערוך]</a>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    const quote_cat_list = <?= json_encode($info['user_cat_list_arr']) ?>;
    function find_quote_cat_by_str(search){
        if(search.length == 1){
            return quote_cat_list.filter((cat) => cat.label.startsWith(search));
        }
        return quote_cat_list.filter((cat) => cat.label.includes(search));
    }
    function select_cat_assign(selected_el){
        const finder_wrap = selected_el.closest(".cat-list-finder-wrap");
        const result_wrap = finder_wrap.querySelector(".cat-list-wrap");
        const result_list = finder_wrap.querySelector(".cat-list-results");
        const apitoui = document.querySelector(".api-to-ui");
        result_wrap.classList.add("hidden");
        result_list.innerHTML = "";
        const cat_id = selected_el.dataset.cat_id;
        const cat_assign_el = apitoui.querySelector(".cat-assign-template").cloneNode(true);
        
        cat_assign_el.querySelector(".cat-label").innerHTML = selected_el.innerHTML;
        cat_assign_el.querySelector(".assign-input").value = cat_id;
        cat_assign_el.dataset.cat_id = cat_id;
        finder_wrap.append(cat_assign_el);
        finder_wrap.querySelector(".list-select").value = "";
    }

    function nav_to_cat_edit(a_el){
        const a_parent = a_el.closest(".cat-assign-checkbox");
        const edit_url = "<?= inner_url('quote_cats/edit/?row_id=') ?>"+a_parent.dataset.cat_id;
        window.open(edit_url, "_new");

    }

    function close_result_list(close_el){
        const finder_wrap = close_el.closest(".cat-list-finder-wrap");
        const result_wrap = finder_wrap.querySelector(".cat-list-wrap");
        const result_list = finder_wrap.querySelector(".cat-list-results");
        result_wrap.classList.add("hidden");
        result_list.innerHTML = "";
        finder_wrap.querySelector(".list-select").value = "";
    }

    function list_quote_cat_options(input){
        const finder_wrap = input.closest(".cat-list-finder-wrap");
        const result_wrap = finder_wrap.querySelector(".cat-list-wrap");
        const result_list = finder_wrap.querySelector(".cat-list-results");
        const apitoui = document.querySelector(".api-to-ui");
        result_list.innerHTML = "";
        //console.log(result_list);
        if(input.value.length < 1){
           // return;
        }
        const found_list = find_quote_cat_by_str(input.value);
        found_list.forEach(cat => {

            const cat_result_el = apitoui.querySelector(".cat-result-template").cloneNode(true);
            console.log(cat_result_el);
            cat_result_el.innerHTML = cat.label;
            cat_result_el.dataset.cat_id = cat.id;
            result_wrap.classList.remove("hidden");
            result_list.append(cat_result_el);
            
           
        });
        //console.log(found_list);
        
        return;
    }
   
    function remove_cat_assign(x_el){
        const cat_wrap = x_el.closest(".cat-assign-template");
        cat_wrap.remove();
    }

</script>
<style type="text/css">
    .cat-list-finder-wrap{
        position: relative;
    }
    .cat-list-wrap{
        position: absolute;
        left: 0px;
        z-index: 2;
        background: #c2c2c2;
        padding: 5px;
        margin-top: 5px;
    }
    .cat-assign-checkbox{
        margin-bottom: 10px;
    }
    .close-list-x, .cat-remove-x{
        text-decoration: none;
        font-family: sans-serif;
        color: red;
        font-weight: bold;
    }
    .close-list-x{
        display: block;
        text-align: left;
        padding: 4px 5px 1px;
        
        font-size: 22px;

    }

    .cat-result-template{
        background: #e3e3c4;
        border: 2px solid black;
        padding: 6px 12px;
        margin: 2px 0px;
        font-family: sans-serif;
        cursor: pointer;
        font-weight: bold;
    }
    .cat-result-template:hover{
        background: blue;
    }
</style>

