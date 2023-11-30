<?php
// to debug here, put in js console: help_debug_forms();

	class GalleryModule extends Module{
        
        public $add_models = array("siteGallery");
        public function my_gallery(){
            $user_id = $this->controller->data['page']['user_id'];
            $portal_user = SitePortal_user::find(array('user_id'=>$user_id));
            if(!$portal_user){
                return;
            }
            $this->add_asset_mapping(SiteGallery::$assets_mapping);
            $action_data = $this->decode_action_data_arr();
            $gallery_id = false;
            $gallery_info = false;
            if(isset($action_data['gallery_id'])){
                $gallery_info = siteGallery::get_by_id($action_data['gallery_id']);
            }
            else{
                $gallery_info = siteGallery::find(array('user_id'=>$user_id));
            }
            if($gallery_info){
                $gallery_id = $gallery_info['id'];
            }
            if(!$gallery_id){
                return;
            }

            $gallery_images = siteGallery::get_gallery_images($gallery_id);
            $info = array(
                'gallery'=>$gallery_info,
                'images'=>$gallery_images
            );

            return $this->include_view('gallery/my_gallery.php',$info);
        }

	}
?>