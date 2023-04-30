<?php $this->include_view("products/main_header.php"); ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('products/add/') ?>">הוספת מוצר</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['product_list'] as $product): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('products/edit/') ?>?row_id=<?= $product['id'] ?>" title="ערוך מוצר"><?= $product['label'] ?></a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('products/delete/') ?>?row_id=<?= $product['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

