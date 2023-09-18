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
            <?php foreach($cat['pairs'] as $cat_pair): ?> 
                <small class="new-cat-pair pair-label cat-pair-for-old-<?= $cat_pair['old_cat_id'] ?>">
                    <a class="pair-x" href="javascript://" onclick="pair_remove(this)" data-old_cat_id="<?= $cat_pair['old_cat_id'] ?>" >X</a>
                    <?= $cat_pair['label'] ?>
                </small>
            <?php endforeach; ?>
        </div>
        <div class="col col-tiny">

            <a class="pair-cat-prepare" href="javascript://" onclick="pair_cat_prepare(this)" data-cat_id="<?= $cat['id'] ?>">
                <<-
            </a> 
        </div>           
    </div>
<?php endforeach; ?>