<h1>נותני השירות התואמים לבקשתך</h1>
<?php foreach($info['supplier_cubes'] as $cube): ?>
    <div class="supplier_cubes roundish-cube">
        <div class="suplier-title roundish-title section big-title">
            <a rel="nofollow" href="<?= $cube['link'] ?>" title="<?= $cube['label'] ?>" target="_blank" class="title-a">
                <?= $cube['label'] ?>
            </a>
        </div>
        <div class="amin-section section flex-section">
            <div class="amin-logo">
                <?php if($cube['cube_image'] != ""): ?>
                    <img src="<?= $this->file_master_url_of('cube_image', $cube['cube_image']) ?>" alt="<?= $cube['label'] ?>" />
                <?php else: ?>
                    <?php if($cube['status'] == '2'): ?>
                        <img src="<?= styles_url('style/image/amin1.png') ?>" alt="נמצא אמין" />
                    <?php else: ?>
                        <img src="<?= styles_url('style/image/amin2.png') ?>" alt="חדש בבדיקה" />
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="amin-text">
                <a href = "<?= $cube['link'] ?>" title="<?= $cube['label'] ?>">
                    <?= $cube['label'] ?>
                </a>
                <br/> 
                
                <?= nl2br($cube['more_cities']) ?>
                <?php if($cube['activity_hours'] != ''): ?>
                    <br/>
                    שעות פעילות: <?= $cube['activity_hours'] ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if(is_mobile() && $cube['whatsapp_phone'] != ""): ?>
            <div class="whatsapp-phone section">
                <a href="whatsapp://send?text=<?= $cube['whatsapp_text']; ?>&phone=<?= $cube['whatsapp_phone']; ?>"> 
                    <img src="<?= $this->file_master_url_of('static', 'style/whatsapp.png') ?>" alt='קבל הצעת מחיר בווטסאפ' /> קבל הצעת מחיר בווטסאפ
                </a>
            </div>            
        <?php elseif($cube['phone']): ?>
            <div class="phone-number section">
                טלפון: <?= $cube['phone'] ?>
            </div>
        <?php endif; ?>
        <div class="phone-number section">
            <a href=<?= $cube['link'] ?> title="<?= $cube['label'] ?>">
                אתר אינטרנט <?= $cube['label'] ?>
            </a>
        </div>
    </div>
<?php endforeach; ?>