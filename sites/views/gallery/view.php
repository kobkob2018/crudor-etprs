<?php if($info['cat_list']): ?>
    <div class="gallery-cat-select">
        <form action = "<?= inner_url("gallery/view/") ?>" class="cat-select-form" name="cat_select_form" method="GET" >
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

<?php if($info['gallery_list'] && $info['selected_cat']): ?>
    <div class="module-sub-menu gallery-sub-menu flex-row flex-wrap">
        <?php foreach($info['gallery_list'] as $gallery): ?>
            <div class="sub-item <?= $gallery['selected_str'] ?>">
                <a class="color-b" href = "<?= inner_url("gallery/view/?cat=".$info['selected_cat']."&g=".$gallery['id']) ?>" title="<?= $gallery['label'] ?>"><?= $gallery['label'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if($info['gallery']): ?>

<div id="content_title_wrap" class="title-wrap flex-row flex-wrap">
    <h1 id="content_title" class="main-title grow-1 color-title"><?= $info['gallery']['label']; ?></h1>
    <div id="share_buttons_wrap">
        <?php $this->call_module('share_buttons','print'); ?>
    </div>
</div>
<div id="content_wrap">
    <?php if($info['images'] && isset($info['images'][0])): ?>
        <?php if(!is_mobile()): ?>
            <div class="gallery-images-wrap">
                <?php $image = $info['images'][0]; ?>
                <div class="big-img-wrap">
                    <img class="gallery-big-img" src = "<?= $this->file_url_of('gallery_images',$image['image']) ?>" alt = "<?= $image['label'] ?>" />
                    <br/>
                    <b class="gallery-image-text-holder color-b"><?= $info['images'][0]['label'] ?></b>
                </div>
                <?php if(isset($info['images'][1])): ?>
                    <div class="gallery-thumbs">

                        <?php foreach($info['images'] as $key=>$image): ?>
                            <div class="gallery-thumb">
                                <a href="javascript://" class="thumb-a modal-gallery-a" title="<?= $image['label'] ?>" data-big_img = "<?= $this->file_url_of('gallery_images',$image['image']) ?>" data-gallery_id = "gallery_modal_1" data-img_index = "<?= $key ?>">
                                    <img src = "<?= $this->file_url_of('gallery_images',$image['small_image']) ?>" alt = "<?= $image['label'] ?>"/>
                                    <div class="hidden thumb-text"><?= $image['label'] ?></div> 
                                </a>    
                            </div>
                            <link rel="preload" as="image" href="<?= $this->file_url_of('gallery_images',$image['image']) ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded",()=>{                   
                    let currentThumb = false;
                    setTimeout(function(){
                        const bigImg = document.querySelector(".gallery-big-img");
                        const bigImgWrap = document.querySelector(".big-img-wrap");
                        const imageTextHolder = bigImgWrap.querySelector(".gallery-image-text-holder");
                            document.querySelectorAll(".gallery-thumb a").forEach(thumb=>{
                                if(!currentThumb){
                                    currentThumb = thumb;
                                }
                                thumb.addEventListener("mouseover",()=>{
                                    currentThumb = thumb;
                                    if(bigImg){
                                        if(!bigImgWrap.classList.contains("h-set")){
                                            bigImgWrap.classList.add("h-set");
                                            bigImgWrap.style.height = bigImgWrap.offsetHeight + "px";
                                        }
                                        const textHolder = thumb.querySelector(".thumb-text");
                                        imageTextHolder.innerHTML = textHolder.innerHTML;
                                        bigImg.src = thumb.dataset.big_img;
                                    }
                                });
                            });
                            bigImgWrap.addEventListener("click",function(){
                                currentThumb.click();
                            });
                        });
                    },500);
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
                                            <img src="<?= $this->file_url_of('gallery_images',$image['image']) ?>" alt="<?= $image['label'] ?>" />
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
        <?php else: ?>
            <div class="gallery-images-wrap mobile-gallery-wrap">
                <?php foreach($info['images'] as $key=>$image): ?>
                    <div class="mobile-gallery-image">
                        <img src="<?= $this->file_url_of('gallery_images',$image['image']) ?>" alt="<?= $image['label'] ?>" />
                        <br/>
                        <b class="gallery-image-text color-b"><?= $image['label'] ?></b>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>                       

    <?php endif; ?>
</div>
<?php endif; ?>
