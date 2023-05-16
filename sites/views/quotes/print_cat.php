
<div class="quote-cat-wrap" id="quote_cat_block_<?= $info['cat']['id'] ?>" data-state="<?= $info['cat']['open_state'] ?>">
    <a href="javascript://" class='quote-cat-toggler'>
        <div class="quote-cat-title big-title">
            <?= $info['cat']['label'] ?>
        </div>
    </a>
    <div class="quote-list-wrap">
        <div class="quote-list flex-table <?php echo $info['cat']['open_state'] == "closed" ? "hidden" : "" ?>">
            
            <?= $info['cat']['title_html'] ?>

            <?php foreach($info['list'] as $quote): ?>
                <?= $quote['html'] ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>