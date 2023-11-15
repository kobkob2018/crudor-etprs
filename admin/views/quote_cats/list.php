<div class="flex-box">
    <div class="flex-col">

   
        <h3>רשימת כותבי הצעות מחיר</h3>

        <?php if(!empty($this->data['user_list'])): ?>
            <div class="items-table flex-table">
                <div class="table-th row">
                    <div class="col">שם הלקוח</div>
                    <div class="col">שיוך לתיקיות</div>
                    <div class="col"></div>
                </div>
                <?php foreach($this->data['user_list'] as $user): ?>
                    <div class="table-tr row">
                        <div class="col">  
                            <?= $user['biz_name'] ?> <br/> 
                            <?= $user['full_name'] ?>
                        </div>

                        <div class="col cat-list-select-wrap">
                            <form  class="table-tr row" action = "" method = "POST" >
                                <input type="hidden" name="sendAction" value="assignUserSend" />
                                <input type="hidden" name="assign[]" value="-1" />
                                <input type="hidden" name="assign_user_id" value="<?= $user['id'] ?>" />
                                <div class="cat-list-finder-wrap">
                                    <input type="text" placeholder="חפש רשימה" class="list-select" onkeyup="list_quote_cat_options(this)" />
                                    <div class="cat-list-wrap hidden">
                                        <a class = 'close-list-x' href="javascript://" onclick="close_result_list(this)">
                                            X
                                        </a>
                                    
                                        <div class="cat-list-results">
                                            
                                        </div>
                                    </div>
                                    <?php foreach($user['cats_assigned'] as $cat): ?>
                                        <div class="cat-assign-template">
                                            <input class="assign-input" type="hidden" name="assign[]" value="<?= $cat['id'] ?>"  />
                                            <a class="cat-remove-x" href="javascript://" onclick="remove_cat_assign(this)">X</a>
                                            <span class="cat-label">
                                                <?= $cat['label'] ?>
                                            </span> 
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col"><input type="submit" value="שמור" /></div>
                            </form>

                        </div>
                        <div class="col">
                            <a href = <?= inner_url('quotes/user_list/?user_id='.$user['id']) ?>>
                                רשימת הצעות מחיר
                            </a>
                            <br/>
                            <br/>
                            <a href = <?= inner_url('quotes_user/list/?user_id='.$user['id']) ?>>
                                מאפיינים כלליים בהצעות מחיר של הלקוח
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h4>לא קיימים כותבים</h4>
        <?php endif; ?>
    </div>

    <div class="flex-col">

   
        <h3>רשימת תיקיות של הצעות מחיר</h3>

        <div class="add-button-block-wrap">
            <a class="focus-box button-focus" href="<?= inner_url('quote_cats/add/') ?>">הוספת תיקייה</a>
        </div>

        <?php if(!empty($this->data['cat_list'])): ?>
            <div class="items-table flex-table">
                <div class="table-th row">
                    <div class="col">תווית</div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <?php foreach($this->data['cat_list'] as $cat): ?>
                    <div class="table-tr row">
                        <div class="col">
                            <a href = <?= inner_url('quote_cats/edit/?row_id='.$cat['id']) ?>>
                                <?= $cat['label'] ?>
                            </a>
                        </div>
                        <div class="col">
                            <a href = <?= inner_url('quotes/list/?cat_id='.$cat['id']) ?>>
                                רשימת הצעות מחיר
                            </a>
                        </div>
                        <div class="col">
                            <a class = 'delete-item-x' href="<?= inner_url('/quote_cats/delete/') ?>?cat_id=<?= $cat['id'] ?>">
                                X
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <h4>לא קיימות תיקיות</h4>
        <?php endif; ?>

    </div>
</div>
<div class="api-to-ui hidden">
    <div class="cat-result-template" onclick="select_cat_assign(this)">

    </div>
    <div class="cat-assign-template">
        <input class="assign-input" type="hidden" name="assign[]"  />
        <a class="cat-remove-x" href="javascript://" onclick="remove_cat_assign(this)">X</a>
        <span class="cat-label">

        </span> 
    </div>
</div>
<style type="text/css">
    .flex-box{
        display: flex;
        justify-content: space-around;
    }
</style>

<script type="text/javascript">
    const quote_cat_list = <?= json_encode($info['quote_cat_list_arr']) ?>;
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
        finder_wrap.append(cat_assign_el);
        finder_wrap.querySelector(".list-select").value = "";
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
            return;
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