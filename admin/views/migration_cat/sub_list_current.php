<?php foreach($this->data['current_sub_cat_list'] as $cat): ?>
    <div class="append-sub new-cat table-tr row active-0<?= $cat['active'] ?> is-visible-0<?= $cat['visible'] ?> deep-0<?=  $cat['deep'] ?>">
        <div class="col col-tiny">
            <?= $cat['id'] ?>
            <?php if($cat['visible'] == '0'): ?>
                <b class="red">נסתר!</b>
            <?php endif; ?>
            <?php if($cat['active'] == '0'): ?>
                <b class="red">לא פעיל!</b>
            <?php endif; ?>
        </div>
        <div class="col">
            <div class="deep-0<?=  $cat['deep'] ?>">
                <?= $cat['label'] ?>
            </div>
        </div>
        <div class="col col-tiny">
            <?= $cat['active'] ?>
        </div>
        <div class="col pairs-col awaiting-pairs" data-cat_id="<?= $cat['id'] ?>">
            <a class="pair-cat-fatch" href="javascript://" onclick="fetch_current_category_pairs_custom(this)">
                הצג קטגוריות משוייכות
            </a> 
        </div>
        <div class="col col-tiny">

            <a class="pair-cat-prepare" href="javascript://" onclick="pair_cat_prepare(this)" data-cat_id="<?= $cat['id'] ?>">
                <<-
            </a> 
        </div>           
    </div>
<?php endforeach; ?>