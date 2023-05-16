
<div class="quote-cat-wrap" id="quote_cat_block_<?= $info['cat']['id'] ?>" data-state="<?= $info['cat']['open_state'] ?>">
    <a href="javascript://" class='quote-cat-toggler'>
        <h2 class="quote-cat-title big-title">
            <?= $info['cat']['label'] ?>
        </h2>
    </a>
    <div class="quote-list-wrap <?= $info['cat']['open_state'] == "closed" ? "hidden" : "" ?>">
        <div class="quote-list flex-table">
            
            <?= $info['cat']['title_html'] ?>

            <?php foreach($info['list'] as $quote): ?>
                <?= $quote['html'] ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>