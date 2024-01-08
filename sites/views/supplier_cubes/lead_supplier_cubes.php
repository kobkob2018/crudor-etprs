<?php if(!$info['supplier_cubes']): ?>
    <h3><?= __tr("No service providers to watch") ?></h3>
<?php else: ?>
    <h1><?= __tr("Service providers that match your request") ?></h1>
    <?php foreach($info['supplier_cubes'] as $cube): ?>
        <div class="supplier_cubes roundish-cube">
            <div class="suplier-title roundish-title section big-title">
                <a rel="nofollow" href="<?= $cube['link'] ?>" title="<?= $cube['label'] ?>" target="_blank" class="title-a">
                    <?= $cube['label'] ?>
                </a>
            </div>
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
                    <a class="cube-title" href = "<?= $cube['link'] ?>" title="<?= $cube['label'] ?>">
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
                <a href=<?= $cube['link'] ?> title="<?= $cube['label'] ?>">
                    <?= __tr("Website") ?> <?= $cube['label'] ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>