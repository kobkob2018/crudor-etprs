<?php foreach($info['supplier_cubes'] as $cube): ?>
    <div class="supplier_cubes roundish-cube leftbar-item counted-module" data-link="<?= add_url_params($cube['link'],array('cube_id'=>$cube['id'])) ?>" data-count_url="<?= inner_url("supplier_cube_count/clicks/") ?>?cube_id=<?= $cube['id'] ?>">
        <img src="<?= inner_url("supplier_cube_count/views/") ?>?cube_id=<?= $cube['id'] ?>" style="display:none" />
        <div class="suplier-title roundish-title section big-title">
            <a class="title-a count-clicker" href="javascript://" rel="nofollow" title="<?= $cube['label'] ?>" target="_blank">
                <?= $cube['label'] ?>
            </a>
        </div>
        <?php if($cube['banner']): ?>
            <div class="cube-banner section">
                <a href = "javascript://" class="net-banner count-clicker" <?php if($cube['banner']['goto_href']): ?>data-link="<?= add_url_params($cube['banner']['goto_href'],array('cube_id'=>$cube['id'])) ?>"<?php endif; ?>>
                    <?php if($cube['banner']['video']): ?>       
                        <video class='cube-banner-vid' width="100%" autoplay loop muted="" playsinline <?php if($cube['banner']['image'] != ""): ?> poster="<?= $this->file_master_url_of('net_banners', $cube['banner']['image']) ?>" <?php endif; ?>>
                            <source src="<?= $this->file_master_url_of('net_banners', $cube['banner']['video']) ?>" alt="<?= $cube['banner']['label'] ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif($cube['banner']['image']): ?>
                        <img class='cube-banner-img' src="<?= $this->file_master_url_of('net_banners', $cube['banner']['image']) ?>" alt="<?= $cube['banner']['label'] ?>" />
                    <?php endif; ?>
                </a>
            </div>

        <?php endif; ?>
        <div class="amin-section section">
            <div class="amin-logo">
                <?php if($cube['cube_image'] != ""): ?>
                    <img src="<?= $this->file_master_url_of('cube_image', $cube['cube_image']) ?>" alt="<?= $cube['label'] ?>" />
                <?php else: ?>
                    <?php if($cube['status'] == '2'): ?>
                        <img src="<?= styles_url('style/image/amin1.png') ?>" alt="<?= __tr("Found reliable") ?>" />
                    <?php else: ?>
                        <img src="<?= styles_url('style/image/amin2.png') ?>" alt="<?= __tr("New under exam") ?>" />
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="amin-text">
                <a class="cube-title count-clicker" href="javascript://" title="<?= $cube['label'] ?>">
                    <?= $cube['label'] ?>
                </a>
                <br/> 
                
                <?= nl2br($cube['more_cities']) ?>
                <?php if($cube['activity_hours'] != ''): ?>
                    <br/>
                    <?= __tr("Activity hours") ?>: <?= $cube['activity_hours'] ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if($cube['phone']): ?>
            <div class="phone-number section">
                <?php if(is_mobile()): ?>
                    <?= __tr("Phone") ?>: <a href="tel:<?= $cube['phone'] ?>" title="<?= __tr("Call") ?>"><?= $cube['phone'] ?></a>
                <?php else: ?>
                    <?= __tr("Phone") ?>: <?= $cube['phone'] ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if(is_mobile() && $cube['whatsapp_phone'] != ""): ?>
            <div class="whatsapp-phone section">
                <a href="whatsapp://send?text=<?= $cube['whatsapp_text']; ?>&phone=<?= $cube['whatsapp_phone']; ?>"> 
                    <img src="<?= $this->file_master_url_of('static', 'media/uploads/whatsapp.png') ?>" alt='<?= __tr("Get quote with whatsapp") ?>' /> <?= __tr("Get quote with whatsapp") ?>
                </a>
            </div>    
        <?php endif; ?>        

        <div class="phone-number section">
            <a class="count-clicker" href="javascript://" title="<?= $cube['label'] ?>">
                <?= __tr("Website") ?> <?= $cube['label'] ?>
            </a>
        </div>
    </div>
<?php endforeach; ?>