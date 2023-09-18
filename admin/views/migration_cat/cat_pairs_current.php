<?php foreach($this->data['cat_pairs'] as $cat_pair): ?> 
    <small class="append-sub new-cat-pair pair-label cat-pair-for-old-<?= $cat_pair['old_cat_id'] ?>">
        <a class="pair-x" href="javascript://" onclick="pair_remove(this)" data-old_cat_id="<?= $cat_pair['old_cat_id'] ?>" >X</a>
        <?= $cat_pair['label'] ?>
    </small>
<?php endforeach; ?>