<h3>התאמת קטגוריות ממערכת ישנה</h3>
<?php $this->include_view("migration_site/header.php"); ?>

<div class="items-table flex-table">
    <div class="table-th row">
        <div class="col col-tiny">#</div>
        <div class="col col-tiny">שם</div>

        <div class="col col-tiny">סטטוס</div>
        <div class="col"></div>
    </div>
    <?php foreach($this->data['migrate_cat_list'] as $cat): ?>
        <div class="table-tr row cat_status-0<?= $cat['status'] ?> deep-0<?=  $cat['deep'] ?>">
            <div class="col col-tiny">
                <?= $cat['id'] ?>
				<?php if($cat['hidden'] == '1'): ?>
					<b class="red">נסתר!</b>
				<?php endif; ?>
                <?php if($cat['status'] == '2'): ?>
					<b class="red">לא פעיל!</b>
				<?php endif; ?>
            </div>
            <div class="col col-tiny">
                <?= $cat['cat_name'] ?>
            </div>
            <div class="col col-tiny">
                <?= $cat['status'] ?>
            </div>
            <div class="col col-tiny">
                <a href="javascript://" onclick="import_cat_prepare(this)" data-cat_id="<?= $cat['id'] ?>">
                    <<--
                </a> 
            </div>           
        </div>
    <?php endforeach; ?>
</div>


<style type="text/css">
	.is-hidden-01{background: gray; }

</style>