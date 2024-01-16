<?php $this->include_view("net_directories/header.php"); ?>

<h3>באנרים בתיקייה</h3>

<div class="add-button-wrap">
    <a class="button-focus" href="<?= inner_url('net_banners/add/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>">הוספת באנר בתיקייה</a>
</div>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col">
            עדכון באנר
            <hr/> 
            <div class="col-table-30">
                <div class="col-30">
                    צפיות
                </div>
                <div class="col-30">
                    קליקים
                </div>
                <div class="col-30">
                    המרות
                </div>
            </div>
        </div>
        <div class="col">פעיל</div>
        <div class="col">מחיקה</div>
    </div>
    <?php foreach($this->data['net_banners'] as $banner): ?>
        <div class="table-tr row">
            <div class="col">
                <a href = "<?= inner_url('net_banners/edit/') ?>?dir_id=<?= $this->data['dir_info']['id'] ?>&row_id=<?= $banner['id'] ?>" title="ערוך באנר"><?= $banner['label'] ?></a>
                <hr/>
                <div class="col-table-30">
                    <div class="col-30">
                        <?= $banner['views'] ?>
                    </div>
                    <div class="col-30">
                        <?= $banner['clicks'] ?>
                        <?php if($banner['views']): ?>
                        <br/>
                            <?= round(intval($banner['clicks'])/intval($banner['views'])*100) ?>%
                        <?php endif; ?>
                    </div>
                    <div class="col-30">
                        <?= $banner['convertions'] ?>
                        <?php if($banner['clicks']): ?>
                            <br/>
                            <?= round(intval($banner['convertions'])/intval($banner['clicks'])*100) ?>%
                        <?php endif; ?>
                    </div>
                </div>
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

