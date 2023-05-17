<div id="content_title_wrap" class="title-wrap flex-row flex-wrap">
    <h1 id="content_title" class="main-title grow-1 color-title"><?= $info['product']['title']; ?></h1>
    <div id="share_buttons_wrap">
        <?php $this->call_module('share_buttons','print'); ?>
    </div>
</div>
<div id="content_wrap">
    <div class="product-images-wrap">
        <?php if($info['images'] && isset($info['images'][0])): $image = $info['images'][0]; ?>
            <img src = "<?= $this->file_url_of('product_images',$image['small_image']) ?>" $title = "<?= $image['label'] ?>" />
            <img src = "<?= $this->file_url_of('product_images',$image['image']) ?>" $title = "<?= $image['label'] ?>" />
        <?php endif; ?>
        <?php foreach($info['images'] as $image): ?>
            <div class="product-image">
            <img src = "<?= $this->file_url_of('product_images',$image['small_image']) ?>" $title = "<?= $image['label'] ?>" />
            <img src = "<?= $this->file_url_of('product_images',$image['image']) ?>" $title = "<?= $image['label'] ?>" />
            </div>
        <?php endforeach; ?>
    </div>  
    <div class="product-content">
        <?= $info['product']['content'] ?>
        <div class="clear"></div>
    </div>
</div>