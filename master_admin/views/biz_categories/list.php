<?php $this->include_view("biz_categories/biz_cat_header.php"); ?>

<?php $this->include_view("crud/item_move_block.php"); ?>


<?php if(isset($this->data['move_item'])): ?>
    <div class="move-item-button-wrap">
        <a class='go-button' href="<?= inner_url('biz_categories/list/') ?>?row_id=<?= $this->data['current_item_id'] ?>&move_item=here">העבר לכאן</a>
    </div>
<?php else: ?>
    <div class="add-button-wrap">
        <a class="button-focus" href="<?= inner_url('biz_categories/add/') ?>?row_id=<?= $this->data['current_item_id'] ?>">הוספת קטגוריה</a>
    </div>
<?PHP endif; ?>


<?php if(!empty($this->data['cat_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">קטגוריה</div>
            <div class="col">עריכה</div>
            <div class="col">העברה</div>
            <div class="col">מחיקה</div>
        </div>
        <?php foreach($this->data['cat_list'] as $cat): ?>
            <div class="table-tr row">
                <div class="col">
                    <a href = "<?= inner_url('biz_categories/list/') ?>?row_id=<?= $cat['id'] ?>" title="בחירה"><?= $cat['label'] ?></a>
                </div>
                <div class="col">
                    <a href = "<?= inner_url('biz_categories/edit/') ?>?row_id=<?= $cat['id'] ?>" title="עריכה">עריכה</a>
                </div>
                <div class="col">

                    <?php if(isset($this->data['move_item'])): ?>
                        <a class='go-button' href="<?= inner_url('biz_categories/list/') ?>?row_id=<?= $cat['id'] ?>&move_item=here">העבר לכאן</a>
                    <?php else: ?>

                        <a href = "<?= inner_url('biz_categories/list/') ?>?move_item=<?= $cat['id'] ?>" title="העברה">העברה</a>
                    <?PHP endif; ?>
                
                
                </div>
                <div class="col">
                    <a href = "<?= inner_url('biz_categories/delete/') ?>?row_id=<?= $cat['id'] ?>" title="מחק">מחק</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>אין תתי קטגוריה</h4>
<?php endif; ?>