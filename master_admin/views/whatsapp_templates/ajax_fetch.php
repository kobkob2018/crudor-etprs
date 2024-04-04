<div class="template-info-holder">
    <?php if($info['template']['header_image'] != ''): ?>
        <div class="image-info-holder" data-image_url="$info['template']['header_image']" ></div>
    <?php endif; ?>
    <?php if($info['template']['header_video'] != ''): ?>
        <div class="video-info-holder" data-image_url="$info['template']['header_vidoe']" ></div>
    <?php endif; ?>
    <div class="text-info-holder">
        <?= $info['template']['text'] ?>
    </div>
</div>