<h3>הצעות מחיר - תיקיות</h3>

<h4>הוספת תיקייה</h4>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">תווית</div>
        <div class="col"></div>
    </div>

    <form  class="table-tr row" action = "" method = "POST" >
        <input type="hidden" name="sendAction" value="create_catSend" />
        <input type="hidden" name="db_row_id" value="new" />

        <div class="col">
            <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label') ?>" />
        </div>

        <div class="col"><input type="submit" value="שמור" /></div>
    </form>


</div>

<hr/>


<h4>רשימת תיקיות של הצעות מחיר</h4>


<?php if(!empty($this->data['item_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">תווית</div>
            <div class="col"></div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['item_list'] as $item): ?>
            <form  class="table-tr row" action = "" method = "POST" >
                <input type="hidden" name="sendAction" value="update_catSend" />
                <input type="hidden" name="db_row_id" value="<?= $item['id'] ?>" /> 
                <div class="col">
                    <input type="text" class = 'table-input' name = 'row[label]' value = "<?= $this->get_form_input('label',$item['form_identifier']) ?>" />
                    <br/>
                    <a href = "<?= inner_url('quotes/list/') ?>?cat_id=<?= $item['id'] ?>" title="בחירה">רשימת הצעות מחיר בתיקייה</a>
                </div>
                <div class="col"><input type="submit" value="שמור" /></div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('/quotes/delete_cat/') ?>?cat_id=<?= $item['id'] ?>">
                        X
                    </a>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין תתי ערים</h4>
<?php endif; ?>