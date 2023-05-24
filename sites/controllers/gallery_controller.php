<?php
  class GalleryController extends CrudController{
    public $add_models = array("siteGallery");

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function error() {
        header('HTTP/1.0 404 Not Found');
        $this->include_view('pages/error.php');
    }

    protected function view(){


        $site_id = $this->data['site']['id'];

        $cat_list = siteGallery::get_site_cat_list($site_id);
        if(!$cat_list){
            $cat_list = array();
        }
        $selected_cat = false;

        if(isset($cat_list[0]['id'])){
            $selected_cat = $cat_list[0]['id'];
        }
        if(isset($_REQUEST['cat'])){
            $selected_cat = $_REQUEST['cat'];
        }
        foreach($cat_list as $key=>$cat){
            $cat_list[$key]['selected_str'] = "";
            if($cat['id'] == $selected_cat){
                $cat_list[$key]['selected_str'] = " selected ";
            }
        }

        $gallery_list = false;
        $gallery_info = false;

        if($selected_cat){
            $gallery_list = siteGallery::get_cat_gallery_list($selected_cat);
        }

        foreach($gallery_list as $gallery_key=>$galery){
            $gallery_list[$gallery_key]['selected_str'] = "";
        }
        $selected_gallery = false;
        if(isset($gallery_list[0])){
            $selected_gallery = $gallery_list[0]['id'];
        }
        if(isset($_REQUEST['g'])){
            $selected_gallery = $_REQUEST['g'];
        }
    
        foreach($gallery_list as $key=>$gallery){
            if($gallery['id'] == $selected_gallery){
                $gallery_list[$key]['selected_str'] = " selected ";
                $gallery_info = $gallery_list[$key];
            }
        }
        $gallery_images = false;
        if($selected_gallery){    
            $gallery_images = siteGallery::get_gallery_images($selected_gallery);
        }
        $info = array(
            'gallery'=>$gallery_info,
            'images'=>$gallery_images,
            'cat_list'=>$cat_list,
            'selected_cat'=>$selected_cat,
            'gallery_list'=>$gallery_list
        );
        $this->add_asset_mapping(SiteGallery::$assets_mapping);

        return $this->include_view('gallery/view.php',$info);
    }
  }
?>