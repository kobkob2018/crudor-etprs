<?php foreach($info['list'] as $page): ?>
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
<?php if($info['pages'] > 1): $p=0; ?> 
    <div class="pagination">
        <?php while($p<$info['pages']): $p++; ?>
            <a class="pagination-a is-current-0<?= $p - 1 == $info['current'] ?>" href="<?= current_clean_url(array('p'=>$p)) ?>" title="page <?= $p ?>" ><?= $p ?></a>  
            <?php if($p != $info['pages']): ?>
                 | 
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
<?php endif; ?>