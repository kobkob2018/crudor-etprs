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
        
        $cat_list = siteGallery::get_site_cat_list($this->data['site']['id']);
        if(!$cat_list){
            $cat_list = array();
        }

        foreach($cat_list as $cat_key=>$cat){
            $cat_list[$cat_key]['selected_str'] = "";
        }
        if(isset($_REQUEST['cat_id'])){
            $cat_id = $_REQUEST['cat_id'];
            $this->data['cat_id'] = $cat_id;
            foreach($cat_list as $key=>$cat){
                if($cat['id'] == $cat_id){
                    $cat_list[$key]['selected_str'] = " selected ";
                }
            }

            $gallery_list = siteGallery::get_cat_gallery_list($cat_id);
            
            foreach($gallery_list as $gallery_key=>$galery){
                $gallery_list[$gallery_key]['selected_str'] = "";
            }
            if(isset($_REQUEST['gallery_id'])){
                $gallery_id = $_REQUEST['gallery_id'];
                foreach($gallery_list as $key=>$gallery){
                    if($gallery['id'] == $gallery_id){
                        $gallery_list[$key]['selected_str'] = " selected ";
                    }
                }
                
                $this->data['gallery_id'] = $gallery_id;
                $this->data['gallery_images'] = siteGallery::get_gallery_images($gallery_id);
            }
            $this->data['gallery_list'] = $gallery_list;

        }
        $this->data['cat_list'] = $cat_list;
        return $this->include_view('gallery/view.php');
    }
  }
?>