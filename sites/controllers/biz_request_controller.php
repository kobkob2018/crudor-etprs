<?php
//http://love.com/biz_form/submit_request/?form_id=4&submit_request=1&biz[cat_id]=52&biz[full_name]=demo_post2&biz[phone]=098765432&biz[email]=no-mail&biz[city]=6&cat_tree[0]=47&cat_tree[1]=52
  class Biz_requestController extends CrudController{
    public $add_models = array("leads_complex","siteSupplier_cubes");


    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function view(){

        if(!isset($_REQUEST['r'])){
            return;
        }
        $this->add_asset_mapping(SiteSupplier_cubes::$assets_mapping);
        $request_id = $_REQUEST['r'];
        $info = Leads_complex::get_request_info_with_users($request_id);
        $this->include_view("supplier_cubes/lead_supplier_cubes.php", $info);
    }

  }
?>