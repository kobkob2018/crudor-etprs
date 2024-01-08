<h1 class="color-title title-wrap"><?= __tr("Search result") ?> "<?= $info['search'] ?>"</h1>

<?php if(empty($info['pages_list'])): ?>
    <b class="red"><?= __tr("No search matches found") ?></b>
<?php endif; ?>

<?php if(!empty($info['pages_list'])): ?>
    <h2 class="color-b title-wrap"><?= __tr("Page results") ?></h2>
<?php endif; ?>

<?php foreach($info['pages_list'] as $page): ?>
    <div class="c-block list-article">
        <a class="list-article-title a-wrap" href = "<?= inner_url($page['link']) ?>" title="<?= $page['title'] ?>" >
            <h2 class="color-title">
                <?= $page['title']; ?>
            </h2>
        </a>
        <div class="list-article-body">
            <?php if($page['right_banner'] != ''): ?>
                <a class="list-article-banner a-wrap" href = "<?= inner_url($page['link']) ?>" title="<?= $page['title'] ?>" >
                    <img src = "<?= $this->file_url_of('right_banner', $page['right_banner']) ?>" alt = "<?= $page['title'] ?>" width="138" />
                </a>
            <?php endif; ?>
            <a class="list-article-content a-wrap" href = "<?= inner_url($page['link']) ?>" title="<?= $page['title'] ?>" >
                <?= $page['content'] ?>
            </a>
        </div>
        <div class="list-article-button-wrap">

            <a class="list-article-button a-wrap color-button nice-button" href = "<?= inner_url($page['link']) ?>" title="<?= $page['title'] ?>" >
                <?= __tr("View post") ?>
            </a> 
        </div>
        <div class="clear"></div>      
    </div>
<?php endforeach; ?>

<?php if(!empty($info['pages_list'])): ?>
    <h2 class="color-b title-wrap"><?= __tr("Product results") ?></h2>
<?php endif; ?>

<?php foreach($info['product_list'] as $product): ?>
    <div class="c-block list-article">
        <a class="list-article-title a-wrap" href = "<?= $product['url'] ?>" title="<?= $product['title'] ?>" >
            <h2 class="color-title">
                <?= $product['title']; ?>
            </h2>
        </a>
        <div class="list-article-body">
            <?php if($product['image'] != ''): ?>
                <a class="list-article-banner a-wrap" href = "<?= $product['url'] ?>" title="<?= $product['title'] ?>" >
                    <img src = "<?= $this->file_url_of('product_image', $product['image']) ?>" alt = "<?= $product['title'] ?>" width="138" />
                </a>
            <?php endif; ?>
            <a class="list-article-content a-wrap" href = "<?= $product['url'] ?>" title="<?= $product['title'] ?>" >
                <?= nl2br($product['description']) ?>
            </a>
        </div>
        <div class="list-article-button-wrap">
            <a class="list-article-button a-wrap color-button nice-button" href = "<?= $product['url'] ?>" title="<?= $product['title'] ?>" >
                <?= __tr("View product") ?>
            </a> 
        </div>
        <div class="clear"></div>      
    </div>
<?php endforeach; ?>