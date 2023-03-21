<?php $this->include_view("net_directories/header.php"); ?>

<h3>באנרים בתיקייה</h3>

<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('net_banners/add/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>">הוספת באנר בתיקייה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">עדכון באנר</div>
        <div class="col">פעיל</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['net_banners'] as $banner): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('net_banners/edit/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>&row_id=<?= $banner['id'] ?>" title="ערוך באנר"><?= $banner['label'] ?></a>
            </div>
            <div class="col">
                <?= $banner['active_str'] ?>
            </div>
            <div class="col">
                <a href = "<?= inner_url('net_banners/delete/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>&row_id=<?= $banner['id'] ?>" title="מחק">מחק</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

