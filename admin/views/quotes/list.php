<?php $this->include_view("quote_cats/header.php"); ?>

<div class="add-item-wrap">
    <a class="focus-box button-focus" href="<?= inner_url('quotes/add/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>">הוספת הצעת מחיר</a>
</div>
<div class="focus-box">
    באפשרותך לשייך הצעות מחיר ללקוח על ידי הוספתן לתור, ואז בעמוד הצעות המחיר של הלקוח, ללחוץ על כפתור השיוך.
    ניתן להוסיף כל הצעת מחיר בנפרד, בטבלה או את כולן ביחד: 
    <a href = "<?= inner_url('quotes/enter_queue/') ?>?return_to=list&cat_id=<?= $this->data['cat_info']['id'] ?>&row_id=all" title="הוספת כל הצעות המחיר לתור">לחץ כאן להוסיף את כל הצעות המחיר בתיקייה לתור</a>
</div>
<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">שם\עריכה</div>
        <div class="col">שיוך ללקוח (הוספה לתור)</div>
        <div class="col">מחיקה</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['quote_list'] as $quote): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('quotes/edit/') ?>?cat_id=<?= $this->data['cat_info']['id'] ?>&row_id=<?= $quote['id'] ?>" title="ערוך הצעת מחיר"><?= $quote['label'] ?></a>
            </div>

            <div class="col">
                <a href = "<?= inner_url('quotes/enter_queue/') ?>?return_to=list&cat_id=<?= $this->data['cat_info']['id'] ?>&row_id=<?= $quote['id'] ?>" title="הוספה לתור">הוספה לתור</a>
            </div>

            <div class="col">
                <a href = "<?= inner_url('quotes/delete/') ?>?row_id=<?= $quote['id'] ?>&cat_id=<?= $this->data['cat_info']['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

