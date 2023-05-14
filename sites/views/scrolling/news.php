<?php if(!empty($info['news'])): ?>
    <div class="kova ">
        
        <h3 class="big-title">חדשות האתר</h3>
        <div class="kova-content">
            <div class="news-ticker-wrap">
                <div class="news-ticker">

                    <?php foreach($info['news'] as $news_post): ?>
                        <div class="new-item">

                            <h4 class="color-title">
                                <?= $news_post['label'] ?>
                            </h4>
                            <div class="item-content">
                                <?php if($news_post['link'] == ""): ?>
                                    <?= nl2br($news_post['content']) ?>
                                <?php else: ?>
                                    <a class= "news-ticker-a-content" href = "<?= $news_post['link'] ?>" title = "לצפייה בהמשך הכתבה">
                                        <?= nl2br($news_post['content']) ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
    <?php $this->register_script('js','news_ticker',styles_url('style/js/news_ticker.js'),'foot'); ?> 
<?php endif; ?>