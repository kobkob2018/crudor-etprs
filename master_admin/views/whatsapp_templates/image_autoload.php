<style type="text/css">
    .image-place-holder img{
        max-width:200px;
    }
    .message_image img{max-width: 100px;}
</style>

<script type="text/javascript">
    function init_image_pholder(){

        const image_pholder = document.createElement('div');
        const image_form_group = document.querySelector(".image-form-group");
        const image_form_group_wrap = image_form_group.querySelector(".form-group-en");
        image_form_group_wrap.appendChild(image_pholder);
        image_pholder.classList.add('image-place-holder');
        const image_url_holder = image_form_group.querySelector(".form-input");
        image_url_holder.addEventListener('change',evt=>{
            placeImageByNewUrl(evt.target.value,image_pholder);
        });

    }

    function placeImageByNewUrl(url,image_pholder) {
        image_pholder.querySelectorAll("img").forEach(img=>{img.remove()});
        if(url == ''){
            return;
        }
        var image = new Image();
        image.onload = function() {
            if (this.width > 0) {
            //console.log("image exists");
                image_pholder.append(image);
            }
        }
        image.onerror = function() {
            image.remove();
            // console.log("image doesn't exist");
        }
        image.src = url;
    }

    function init_video_pholder(){

        const video_pholder = document.createElement('div');
        const video_form_group = document.querySelector(".video-form-group");
        const video_form_group_wrap = video_form_group.querySelector(".form-group-en");
        video_form_group_wrap.appendChild(video_pholder);
        video_pholder.classList.add('video-place-holder');
        const video_url_holder = video_form_group.querySelector(".form-input");
        video_url_holder.addEventListener('change',evt=>{
            placeVideoByNewUrl(evt.target.value,video_pholder);
        });

    }

    function placeVideoByNewUrl(url,video_pholder) {

        video_pholder.querySelectorAll(".video").forEach(video=>{video.remove()});

        if(url == ''){
            return;
        }
        var video = document.createElement('video');
        video_pholder.append(video);
        video.src = url;
        video.width = "150";
        video.autoplay = true;
        video.onerror = function() {
            video.remove();
            alert("no good video");
            // console.log("image doesn't exist");
        }

    }
    init_image_pholder();
    init_video_pholder();
</sript>