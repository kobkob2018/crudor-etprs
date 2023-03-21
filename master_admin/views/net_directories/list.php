<h3>תיקיות באנרים</h3>

<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('net_directories/add/') ?>">הוספת תיקייה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון תיקייה</div>
        <div class="col">פעיל</div>
        <div class="col">באנרים</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['net_directories'] as $net_directoriy): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('net_directories/edit/') ?>?row_id=<?= $net_directoriy['id'] ?>" title="ערוך תיקייה"><?= $net_directoriy['label'] ?></a>
            </div>
            <div class="col">
                <?= $net_directoriy['active_str'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('net_banners/list/') ?>?dir_id=<?= $net_directoriy['id'] ?>" title="ערוך באנרים">רשימת הבאנרים</a>
            </div>
            <div class="col">
                <a href = "<?= inner_url('net_directories/delete/') ?>?row_id=<?= $net_directoriy['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

