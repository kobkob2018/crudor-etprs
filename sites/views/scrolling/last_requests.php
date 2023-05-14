<?php if(!empty($info['last_requests'])): ?>
    <div class="kova ">
        
        <h3 class="big-title">פניות אחרונות מהאתר</h3>
        <div class="kova-content">
            <div class="news-ticker-wrap">
                <div class="news-ticker">

                    <?php foreach($info['last_requests'] as $biz_request): ?>
                        <div class="new-item">

                            <h4 class="color-title">
                                <?= $biz_request['cat_name'] ?>
                            </h4>
                            <div class="item-content">
                                <?= $biz_request['note'] ?>
                            </div>
                            <div class="item-date smaller">
                                <?= hebdt($biz_request['date_in'],"d-m-Y H:i") ?>
                            </div>                              
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
    <?php $this->register_script('js','news_ticker',styles_url('style/js/news_ticker.js'),'foot'); ?> 
<?php endif; ?>