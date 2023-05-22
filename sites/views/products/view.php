<?php if($info['cat_list']): ?>
    <div class="product-cat-select">
        <form action = "<?= inner_url("products/view/") ?>" class="cat-select-form" name="cat_select_form" method="GET" >
            <select name="cat" class="cat-select select-cat-auto-submit">
                <?php foreach($info['cat_list'] as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['selected_str'] ?> >
                        <?= $cat['label'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <script type = "text/javascript">
        document.querySelectorAll(".select-cat-auto-submit").forEach(function(select){
            const select_form = select.closest(".cat-select-form");
            select.addEventListener('change',function(){

                select_form.submit();
            }
            );
            
        });
    </script>
<?php endif; ?>

<?php if($info['sub_list'] && $info['selected_cat']): ?>
    <div class="module-sub-menu product-sub-menu flex-row flex-wrap">
        <?php foreach($info['sub_list'] as $sub): ?>
            <div class="sub-item <?= $sub['selected_str'] ?>">
                <a class="color-b" href = "<?= inner_url("products/view/?cat=".$info['selected_cat']."&sub=".$sub['id']) ?>" title="<?= $sub['label'] ?>"><?= $sub['label'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if($info['product']): ?>

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
<?php endif; ?>


<?php if($info['product_list']): ?>
    <div id="content_title_wrap" class="title-wrap flex-row flex-wrap">
        <h1 id="content_title" class="main-title grow-1 color-title">קטלוג מוצרים</h1>
        <div id="share_buttons_wrap">
            <?php $this->call_module('share_buttons','print'); ?>
        </div>
    </div>
    <div class="product-list flex-row flex-wrap box-list">
        <?php foreach($info['product_list'] as $product): ?>
            <div class="product-box list-box">
                <div class="box-title">
                    <?= $product['label'] ?>
                </div>

                <div class="box-content">
                    <?php if($product['image']): ?>
                        <div class="box-image">
                            <img src="<?= $this->file_url_of('product_image',$product['image']) ?>" alt="<?= $product['label'] ?>" />
                        </div>
                    <?php endif; ?>
                    <div class="box-text">
                        <?= nl2br($product['description']) ?>
                    </div>
                    <?php if($product['price']): ?>
                        <div class="box-price">
                            <?= $product['price'] ?> ש"ח 
                        </div> 
                    <?php endif; ?>
                    <div class="box-go-to">
                        <a href="<?= current_url(array('p'=>$product['id'])) ?>" title="<?= $product['label'] ?>">
                            למידע נוסף
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
