<?php if($info['cat_list']): ?>
    <div class="product-cat-select">
        <form action = "<?= inner_url("products/view/") ?>" class="cat-select-form" name="cat_select_form" method="GET" >
            <select name="cat" class="cat-select select-cat-auto-submit">
                <option value="">
                    בחר נושא
                </option>
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
        <?php if($info['images'] && isset($info['images'][0])): ?>
            <div class="product-images-wrap">
                <?php $image = $info['images'][0]; ?>
                <div class="big-img-wrap">
                    <img class="product-big-img" src = "<?= $this->file_url_of('product_images',$image['image']) ?>" alt = "<?= $image['label'] ?>" />
                </div>
                <?php if(isset($info['images'][1])): ?>
                    <div class="product-thumbs">

                        <?php foreach($info['images'] as $key=>$image): ?>
                            <div class="product-thumb">
                                <a href="javascript://" class="thumb-a modal-gallery-a" title="<?= $image['label'] ?>" data-big_img = "<?= $this->file_url_of('product_images',$image['image']) ?>" data-gallery_id = "gallery_modal_1" data-img_index = "<?= $key ?>">
                                    <img src = "<?= $this->file_url_of('product_images',$image['small_image']) ?>" alt = "<?= $image['label'] ?>"/>
                                </a>    
                            </div>
                            <link rel="preload" as="image" href="<?= $this->file_url_of('product_images',$image['image']) ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded",()=>{                   
                    const bigImg = document.querySelector(".product-big-img");
                    const bigImgWrap = document.querySelector(".big-img-wrap");
                    bigImgWrap.style.height = bigImgWrap.offsetHeight + "px";
                    document.querySelectorAll(".product-thumb a").forEach(thumb=>{
                        thumb.addEventListener("mouseover",()=>{
                            if(bigImg){
                                bigImg.src = thumb.dataset.big_img;
                            }
                        });
                    });
                });

            </script>

            <div id="modals_placeholder"></div>
            <div id="gallery_modal_1" class="gallery-modal">
                
                <div class="gallery-carusel-wrap">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeDrawer('accessibility')">&times;</a>
                    <div class="gallery-carusel">

                            <div class="mycarusel gallery-carusel">
                                <div class="carusel-control next">
                                <i class="fa carusel-next fa-chevron-right"><</i>
                            </div>
                            <div class="carusel-control previous">
                                
                                <i class="fa carusel-previous fa-chevron-left">></i>
                            </div>
                            <div class="modal-gallery items">
                                
                                <?php foreach($info['images'] as $key=>$image): ?>
                                <div class="gallery-item item item_id_<?= $key ?>">
                                    <div class="item-content">
                                        <div class="item-text">
                                            <h2>
                                            <?= $image['label'] ?>
                                            </h2>
                                        </div>
                                                            
                                        <div class="item-img">
                                            <img src="<?= $this->file_url_of('product_images',$image['image']) ?>" alt="{{ img_title }}" />
                                        </div>

                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->register_script('js','my_carusel_js',styles_url('style/js/my_carusel.js'),'foot'); ?> 
            <?php $this->register_script('style','my_carusel_css',styles_url('style/css/my_carusel.css'),'foot'); ?>



        <?php endif; ?>
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
                <div class="box-title color-b">
                    <?= $product['label'] ?>
                </div>

                <div class="box-content">
                    <?php if($product['image']): ?>
                        <div class="box-image">
                            <a href="<?= current_url(array('p'=>$product['id'])) ?>" title="<?= $product['label'] ?>">
                                <img src="<?= $this->file_url_of('product_image',$product['image']) ?>" alt="<?= $product['label'] ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="box-text">
                        <?= nl2br($product['description']) ?>
                    </div>
                    <?php if($product['price']): ?>
                        <div class="box-price color-b">
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

<?php if($info['more_products'] && $info['product']): ?>
    <div class="midpage-title-wrap">
        <h3 class="color-title">גולשים שהתעניינו ב - <?= $info['product']['label'] ?> התעניינו גם במוצרים הבאים</h3>
    </div>
    <div class="product-list flex-row flex-wrap box-list">
        <?php foreach($info['more_products'] as $product): ?>
            <div class="product-box list-box">
                <div class="box-title color-b">
                    <?= $product['label'] ?>
                </div>

                <div class="box-content">
                    <?php if($product['image']): ?>
                        <div class="box-image">
                            <a href="<?= current_url(array('p'=>$product['id'])) ?>" title="<?= $product['label'] ?>">
                                <img src="<?= $this->file_url_of('product_image',$product['image']) ?>" alt="<?= $product['label'] ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="box-text">
                        <?= nl2br($product['description']) ?>
                    </div>
                    <?php if($product['price']): ?>
                        <div class="box-price color-b">
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
