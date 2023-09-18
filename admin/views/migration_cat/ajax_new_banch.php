<?php foreach($this->data['migrate_cat_list'] as $cat): ?>
    <div class="old-cat old-cat-<?= $cat['id'] ?> table-tr row cat_status-0<?= $cat['status'] ?>  is-hidden-0<?= $cat['hidden'] ?> deep-0<?=  $cat['deep'] ?> has-ad-0<?= $cat['googleADSense'] == '' ? '0': '1' ?>">
        <div class="col col-tiny">
            <a class="pair-button" href="javascript://" onclick="pair_cat_go(this)" data-cat_id="<?= $cat['id'] ?>">
            <<-
            </a> 
        </div>
        <div class="col col-tiny">
            <?= $cat['id'] ?>
            <?php if($cat['hidden'] == '1'): ?>
                <b class="red">נסתר!</b>
            <?php endif; ?>
            <?php if($cat['status'] == '2'): ?>
                <b class="red">לא פעיל!</b>
            <?php endif; ?>

            <?php if($cat['googleADSense'] != ''): ?>
                <br/><br/>
                <b class="red">מכיל פרסומת!</b>
            <?php endif; ?>
        </div>
        <div class="col">
            <div class="deep-0<?=  $cat['deep'] ?>">
                <?= $cat['cat_name'] ?>
            </div>
        </div>
        <div class="col col-tiny">
            <?= $cat['status'] ?>
        </div>
        <div class="col">
            <div class="pair-label">
                <small class="pair-label">
                    <?= $cat['pair_label'] ?>
                </small>
            </div>
        </div>
    </div>
<?php endforeach; ?>