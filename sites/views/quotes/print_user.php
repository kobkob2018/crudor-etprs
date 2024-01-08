<div class="quote-cat-wrap" id="quote_cat_block_<?= $info['quote_user']['user_id'] ?>" data-state="<?= $info['quote_user']['open_state'] ?>">
    <a href="javascript://" class='quote-cat-toggler'>
        <h2 class="quote-cat-title big-title">
            <?= __tr("My quotes") ?>
        </h2>
    </a>
    <div class="quote-list-wrap <?= $info['quote_user']['open_state'] == "closed" ? "hidden" : "" ?>">
        <div class="quote-list flex-table">
            
            <?= $info['quote_user']['title_html'] ?>

            <?php foreach($info['list'] as $quote): ?>
                <?= $quote['html'] ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $this->register_script('js','tree_selector',styles_url('style/js/quotes_lazyload.js'),'foot'); ?> 