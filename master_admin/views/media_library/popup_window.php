<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
  		<base href="<?= outer_url(); ?>" />
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	

		<title><?= $this->data['meta_title'] ?></title>
		<script type="text/javascript">
            
            function select_lib_img(a_el){
                selectedItems = document.querySelectorAll('.item-selected');

                selectedItems.forEach(sel=>{
                    sel.classList.remove("item-selected");
                });
                a_el.classList.add("item-selected");

                document.getElementById('submit_a_button').classList.add('active');
            }

            function submit_image_select(){
                selectedItems = document.querySelectorAll('.item-selected');

                the_selected_el = null;
                selectedItems.forEach(sel=>{
                    the_selected_el = sel;
                });
                if(the_selected_el !== null){
                    image_url = the_selected_el.dataset.image_url;
                    window.opener.update_from_media_library(image_url);
                    window.close();
                }
            }

            function submit_lib_img(s_el){
                image_url = s_el.dataset.image_url;
                window.opener.update_from_media_library(image_url);
                window.close();
            }
        </script>

        <style type ="text/css">
            *{
                box-sizing: border-box;
            }

            .buttom-buttons{
                display: block;
                position: fixed;
                bottom: 0px;
                width: 100%;
                background: wheat;
                padding: 20px;
                box-sizing: border-box;
            }
            .lib-image-wrap{
                float: right;
                height: 130px;
                width: 200px;
                padding: 20px;
                max-height: 100%;
                margin-bottom:20px;

            }
            .lib-image-wrap a {
                display: block;
                width: 100%;
                height: 100%;
                border: 1px solid blue;
                border-radius: 5px;
                color: black;
                text-decoration: none;
            }

            .lib-image-wrap a.item-selected{
                background: blue;
                color: blue;
            }

            .lib-image-wrap img{
                max-width: 100%;

                height: auto;
                max-height: 100%;
                display: block;
                margin: auto;
            }
            .img-wrap{
                height: 100%;
                width: 100%;
                padding: 5px;
            }
            .submit-a{
                display: block;
                float: left;
                background: #dbcdcd;
                border-radius: 5px;
                color: black;
                border: 1px solid black;
                text-decoration: none;
                font-size: 23px;
                padding: 10px 37px;
                box-shadow: 5px 5px 5px grey;
            }
            .submit-a{
                display: none;
            }
            a.submit-a.active{
                display: block;
            }
        </style>

  </head>
  <body style="direction:rtl; text-align:right;" class="<?php echo $this->body_class; ?>">
    <?php foreach($this->data['library_images'] as $image): ?>
        <div id="lib_img_wrap" class = "lib-image-wrap">
            <a href="javascript://" ondblclick = "submit_lib_img(this)" onClick = "select_lib_img(this)" data-image_url = "<?= $image['url'] ?>" >
                <div class='img-wrap'>

                    <img src = "<?= $image['url'] ?>" />
                </div>
                
                <?= $image['name'] ?>
            </a>
            
        </div>
    <?php endforeach; ?>
    <div class="buttom-buttons">
        <a id = "submit_a_button" class="submit-a" href="javascript://" onClick = "submit_image_select()" >
            בחירה
            
        </a>
    </div>
  </body>
<html>