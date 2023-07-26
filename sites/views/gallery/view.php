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
                    <div class="big-img-box">
                        <img class="gallery-big-img" src = "<?= $this->file_url_of('gallery_images',$image['image']) ?>" alt = "<?= $image['label'] ?>" />
                    </div>
                    <br/>
                    <b class="gallery-image-label-holder color-b"><?= $info['images'][0]['label'] ?></b>
                    <div class="gallery-image-description-holder color-b"><?= $info['images'][0]['description'] ?></div>
                </div>
                <?php if(isset($info['images'][1])): ?>
                    <div class="gallery-thumbs-wrap">
                        <div class="gallery-thumbs">

                        </div>
                        <div class="gallery-thumbs-holder hidden">

                            <?php foreach($info['images'] as $key=>$image): ?>
                                <div class="gallery-thumb">
                                    <a href="javascript://" class="thumb-a modal-gallery-a" title="<?= $image['label'] ?>" data-big_img = "<?= $this->file_url_of('gallery_images',$image['image']) ?>" data-gallery_id = "gallery_modal_1" data-img_index = "<?= $key ?>">
                                        <img src = "<?= $this->file_url_of('gallery_images',$image['small_image']) ?>" alt = "<?= $image['label'] ?>"/>
                                        <div class="hidden thumb-label"><?= $image['label'] ?></div> 
                                        <div class="hidden thumb-description"><?= $image['description'] ?></div> 
                                    </a>    
                                </div>
                                <link rel="preload" as="image" href="<?= $this->file_url_of('gallery_images',$image['image']) ?>">
                            <?php endforeach; ?>
                        </div>
                        <div class="gallery-thumbs-controlls hidden">
                            <div class="controll-prev-wrap gallery-controll-wrap">
                                <a class="gallery-controll controll-prev" href="javascript://" onclick="gallery_prev_box()">
                                    <i class="fa carusel-next fa-chevron-right"><</i>
                                </a> 
                            </div>
                            <div class="controll-next-wrap gallery-controll-wrap">
                                <a class="gallery-controll controll-next" href="javascript://" onclick="gallery_next_box()">
                                    <i class="fa carusel-previous fa-chevron-left">></i>
                                </a> 
                            </div>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <script type="text/javascript">
                function galery_swich_box(current,nextI){
                    const nextBox = document.getElementById("thumbBox_"+nextI);
                    current.classList.add("hidden");
                    current.classList.remove("current");
                    nextBox.classList.remove("hidden");
                    nextBox.classList.add("current");
                }
                function gallery_next_box(){
                    const current = document.querySelector(".thumb-box.current");
                    const next = current.dataset.next;
                    galery_swich_box(current,next);
                }
                function gallery_prev_box(){
                    const current = document.querySelector(".thumb-box.current");
                    let prev = current.dataset.prev;
                    if(prev == '-1'){
                        prev = lastThumbI; 
                    }
                    galery_swich_box(current,prev);
                }
                let lastThumbBox = false;
                document.addEventListener("DOMContentLoaded",()=>{                   
                    setTimeout(function(){
                        let currentThumb = false;
                        const bigImg = document.querySelector(".gallery-big-img");
                        const bigImgWrap = document.querySelector(".big-img-wrap");
                        const bigImgBox = document.querySelector(".big-img-box");
                        const galleryThumbsWrap = document.querySelector(".gallery-thumbs");
                        const imageLabelHolder = bigImgWrap.querySelector(".gallery-image-label-holder");
                        const imageDescriptionHolder = bigImgWrap.querySelector(".gallery-image-description-holder");
                        let boxThumbCount = 0;
                        let thumbBoxI = 0;
                        let currentThumbBox = false;
                        document.querySelectorAll(".gallery-thumb").forEach(thumb=>{
                            
                            if(boxThumbCount == 9){
                                document.querySelector(".gallery-thumbs-controlls").classList.remove("hidden");
                                boxThumbCount = 0;
                            }
                            if(boxThumbCount == 0){
                                if(currentThumbBox){
                                    //the 'next' parm of previous box             
                                    currentThumbBox.dataset.next = thumbBoxI;
                                    if(!optimalHeight){
                                        optimalHeight = currentThumbBox.offsetHeight;
                                        galleryThumbsWrap.style.height = optimalHeight + "px";
                                    }
                                }
                                currentThumbBox = document.createElement('div');
                                lastThumbI = thumbBoxI;
                                if(thumbBoxI != 0){
                                    currentThumbBox.classList.add("hidden");
                                }
                                else{
                                    currentThumbBox.classList.add("current");
                                }
                                currentThumbBox.dataset.next = 0; // the current goes back to 0
                                currentThumbBox.dataset.prev = thumbBoxI - 1; // the current goes back to 0
                                currentThumbBox.id = "thumbBox_"+thumbBoxI;
                                currentThumbBox.classList.add("thumb-box");
                                galleryThumbsWrap.append(currentThumbBox);
                                thumbBoxI++; 
                            }
                            currentThumbBox.append(thumb);
                            boxThumbCount++;
                        });
                        document.querySelectorAll(".gallery-thumb a").forEach(thumb=>{
                            if(!currentThumb){
                                currentThumb = thumb;
                            }
                            thumb.addEventListener("mouseover",()=>{
                                currentThumb = thumb;
                                if(bigImg){
                                    if(!bigImgWrap.classList.contains("h-set")){
                                        bigImgWrap.classList.add("h-set");
                                        if(!optimalHeight){
                                            optimalHeight = bigImgBox.offsetHeight;
                                        }
                                        else{
                                            optimalHeight-=11; 
                                        }
                                        bigImgBox.style.height = optimalHeight + "px";
                                    }
                                    const labelHolder = thumb.querySelector(".thumb-label");
                                    imageLabelHolder.innerHTML = labelHolder.innerHTML;
                                    const descriptionHolder = thumb.querySelector(".thumb-description");
                                    imageDescriptionHolder.innerHTML = descriptionHolder.innerHTML;
                                    bigImg.src = thumb.dataset.big_img;
                                    
                                }
                            });
                        });
                        bigImgWrap.addEventListener("click",function(){
                            currentThumb.click();
                        });
                    },500);
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
                                        <div class="item-title">
                                            <h2>
                                            <?= $image['label'] ?>
                                            </h2>
                                        </div>
                                                            
                                        <div class="item-img">
                                            <img src="<?= $this->file_url_of('gallery_images',$image['image']) ?>" alt="<?= $image['label'] ?>" />
                                        </div>
                                        <?php if($image['description'] != ""): ?>
                                            <div class="item-text">
                                                <?= nl2br($image['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->register_script('js','my_carusel_js',styles_url('style/js/my_carusel.js?cache='.get_config('cash_version')),'foot'); ?> 
            <?php $this->register_script('style','my_carusel_css',styles_url('style/css/my_carusel.css?cache='.get_config('cash_version')),'foot'); ?>
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
