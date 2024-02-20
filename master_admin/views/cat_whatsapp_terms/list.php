<?php $this->include_view("biz_categories/biz_cat_header.php"); ?>

<h2>
    מושגי התאמה ללידים מהווטסאפ
</h2>
    <h4>הוספת מושג</h4>
    <div class="items-table flex-table">
        <div class="table-th row">    
            <div class="col">מושג</div>
            <div class="col"></div>
        </div>

        <form  class="table-tr row" action = "<?= inner_url("cat_whatsapp_terms/add/") ?>?cat_id=<?= $this->data['current_item_id'] ?>" method = "POST" >
            <input type="hidden" name="sendAction" value="createSend" />
            <input type="hidden" name="db_row_id" value="new" />
           
            <div class="col">
                <input type="text" class = 'table-input' name = 'row[term]' value = "<?= $this->get_form_input('term') ?>" />
            </div>

            <div class="col"><input type="submit" value="שמור" /></div>
        </form>


    </div>

    <hr/>
<?php if(!empty($this->data['term_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col col-tiny">#</div>
            <div class="col">עריכה</div>
            <div class="col">מחיקה</div>
        </div>
        <?php foreach($this->data['term_list'] as $term): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="listUpdateSend" />
                <input type="hidden" name="db_row_id" value="<?= $term['id'] ?>" /> 
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[term]' value = "<?= $this->get_form_input('term',$term['form_identifier']) ?>" />
                </div>

                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('cat_whatsapp_terms/delete/') ?>?cat_id=<?= $term['cat_id'] ?>&row_id=<?= $term['id'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין מושגים לקטגוריה זו</h4>
<?php endif; ?>