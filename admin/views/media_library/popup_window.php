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
                document.getElementById('delete_a_button').classList.add('active');
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

            function delete_selected_image(){
                if(!confirm("האם למחוק תמונה זו?")){
                    return;
                }
                const s_el = document.querySelector('.item-selected');
                const image_url = s_el.dataset.image_url;
                fetch("<?= inner_url("media/delete_image/") ?>?image="+image_url).then((res) => res.json()).then(info => {
                    if(info.success){
                        alert("התמונה נמחקה בהצלחה");
                        s_el.remove();
                    }
                    else{
                        if(info.message != ""){
                            alert(info.message);
                        }
                        return;
                    }
                });
            }
            function toggle_block(a_el, block_id){
                const block = document.querySelector("."+block_id);
                if(!block){
                    return;
                }
                if(block.dataset.view_state == 'show'){
                    hide_block(a_el, block_id);
                }
                else{
                    show_block(a_el, block_id);
                }
            }

            function hide_block(a_el, block_id){
                const block = document.querySelector("."+block_id);
                block.classList.add("closed");
                block.dataset.view_state = 'closed';
                a_el.classList.remove('open');
            }

            function show_block(a_el, block_id){
                const block = document.querySelector("."+block_id);
                block.classList.remove("closed");
                block.dataset.view_state = 'show';
                a_el.classList.add('open');
            }
        </script>

        <style type ="text/css">
            *{
                box-sizing: border-box;
            }

            .buttom-buttons{
                display: flex;
                align-items: self-end;
                justify-content: space-between;
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
            .delete-a{
                color:red;
                font-size: 20px;
            }
            .submit-a, .delete-a{
                display: none;
            }
            a.submit-a.active,a.delete-a.active{
                display: block;
            }

            .folder-menu-item.selected{
                text-decoration: none;
                color: black;
                font-weight: bold;
            }
            .focus-box{
                padding: 10px;
                background: #f5efef;
                border: 1px solid gray;
                border-radius: 5px;
                box-shadow: 5px 5px 5px #ababab;
                max-width: 1050px;
                margin: 20px auto;
                display: block;
            }
            .portal-dir-label{
                display: flex;
                align-items: center;
                font-size: 18px;
            }
            .folder-menu-door img{
                width: 20px;
                margin-right: 10px;
                rotate: 0deg;
                transition: all 0.5s;

            } 
            .folder-menu-door.open img{
                rotate: -90deg;
                margin-right:40px ;
            }
            .toggled{
                overflow: hidden;
                max-height: 200px;
                transition: all 1s;
                overflow: auto;
            }
            .closed{
                max-height: 0px;
                overflow: hidden;
            }
        </style>

  </head>
  <body style="direction:rtl; text-align:right;" class="<?php echo $this->body_class; ?>">
        <?php if(isset($this->data['selected_portal_dir'])): ?>
            <div class="portal-folder-menu-wrap focus-box">
                <div class="portal-dir-label">
                    אתה נמצא בתיקייה: <b><?= $this->data['selected_portal_dir']['label'] ?></b>
                    <a class="folder-menu-door" href="javascript://" onclick="toggle_block(this, 'portal-folder-menu')">
                        <img src="style/image/left-icon.png" alt="פתח תפריט תיקיות"/>
                    </a>
                </div>
        
                <?php if(isset($this->data['portal_users'])): ?>
                    <div class="portal-folder-menu toggled closed">
                        <a href="<?= inner_url('media/librarypopup/') ?>" title="תיקייה ראשית" class="folder-menu-item <?= $this->data['main_portal_dir']['selected'] ?>"><?= $this->data['main_portal_dir']['label'] ?></a>
                        <?php foreach($this->data['portal_users'] as $portal_user): ?>
                            | <a href="<?= inner_url('media/librarypopup/?portal='.$portal_user['user_id']) ?>" title="צפה בהעלאות" class="folder-menu-item <?= $portal_user['selected'] ?>"><?= $portal_user['full_name'] ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
            <a id = "delete_a_button" class="delete-a" href="javascript://" onClick = "delete_selected_image()" >
                מחיקת תמונה       
            </a>
            <a id = "submit_a_button" class="submit-a" href="javascript://" onClick = "submit_image_select()" >
                בחירה       
            </a>
        </div>
  </body>
<html>