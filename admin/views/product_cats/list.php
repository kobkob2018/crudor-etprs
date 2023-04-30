<?php $this->include_view("products/main_header.php"); ?>
<h3>רשימת תיקיות של מוצרים</h3>

<div class="add-button-block-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('product_cats/add/') ?>">הוספת תיקייה</a>
</div>

<?php if(!empty($this->data['cat_list'])): ?>
    <div class="items-table flex-table">
        <div class="table-th row">
            <div class="col">תווית</div>
            <div class="col"></div>
        </div>
        <?php foreach($this->data['cat_list'] as $cat): ?>
            <div class="table-tr row">
                <div class="col">
                    <a href = <?= inner_url('product_cats/edit/?row_id='.$cat['id']) ?>>
                        <?= $cat['label'] ?>
                    </a>
                </div>
                <div class="col">
                    <a class = 'delete-item-x' href="<?= inner_url('product_cats/delete/') ?>?row_id=<?= $cat['id'] ?>">
                        X
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h4>לא קיימות תיקיות</h4>
<?php endif; ?>