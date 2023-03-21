<?php $this->include_view("/quotes/cat_header.php"); ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('quotes/add/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>">הוספת הצעת מחיר</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['quote_list'] as $quote): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('quotes/edit/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>&row_id=<?= $quote['id'] ?>" title="ערוך הצעת מחיר"><?= $quote['label'] ?></a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('quotes/delete/') ?>?row_id=<?= $quote['id'] ?>&cat_id=<?= $this->data['cat_info']['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

