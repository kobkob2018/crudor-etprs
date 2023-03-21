<?php foreach($info['net_banners'] as $banner): ?>
    
    <div class="leftbar-net-banner net-banner">
        <img src="<?= inner_url("banner_count/views/") ?>?banner_id=<?= $cube['banner']['id'] ?>" style="display:none" />
        <?php if($banner['image'] != ''): ?>
            <a href = "javascript://" class="banner-clicker" data-link="<?= add_url_params($banner['goto_href'],array('banner_id'=>$banner['id'])) ?>" data-count_url="<?= inner_url("banner_count/clicks/") ?>?banner_id=<?= $banner['id'] ?>">
                <img class='banner-img' src="<?= $this->file_master_url_of('net_banners', $banner['image']) ?>" alt="<?= $banner['label'] ?>" />
            </a>
        <?php endif; ?>

        <?php if($banner['video'] != ''): ?>
            <a href = "javascript://" class="banner-clicker" data-link="<?= add_url_params($banner['goto_href'],array('banner_id'=>$banner['id'])) ?>" data-count_url="<?= inner_url("banner_count/clicks/") ?>?banner_id=<?= $banner['id'] ?>">
                <video class='cube-banner-vid' width="100%" controls="">
                    <source src="<?= $this->file_master_url_of('net_banners', $banner['video']) ?>" alt="<?= $banner['label'] ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </a>
        <?php endif; ?>

        <?php if($banner['free_html'] != ''): ?>
            <div class="banner-free-html">
                <?= $banner['free_html'] ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>