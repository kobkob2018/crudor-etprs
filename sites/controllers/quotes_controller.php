<?php
  class QuotesController extends CrudController{
    public $add_models = array("siteQuotes");

    public function fetch_users(){
        $this->set_layout("blank");
        $this->add_asset_mapping(SiteQuotes::$asset_mapping);
        $return_array = array('res'=>'ok','users'=>array());
        $request_body = file_get_contents('php://input');

        $request_data = json_decode($request_body,true);
        if(!isset($request_data['users'])){
            $return_array['res'] = 'no-users';
        }
        else{
            $cash_version = get_config('cash_version');
            $fetch_users = array();
            $return_array['users'] = array();
            foreach($request_data['users'] as $user_id){
                $fetch_users[$user_id] = $user_id;
            }
            foreach($fetch_users as $user_id){
                
                $user_info = SiteQuotes::simple_find_by_table_name(array('user_id'=>$user_id),'quotes_user');
                $img_url = $this->file_url_of('quotes_user_img',$user_info['image'],'master');

                $user_info['image_url'] = $img_url."?cache=".$cash_version;
                $return_array['users'][$user_id] = $user_info;
            }
        }
        return print(json_encode($return_array,true));
    }

    public function cat_demo(){
        $this->data['add_nofollow_tag'] = true;
        $cat_id = $_REQUEST['cat_id'];
        
        echo("{{% mod | quotes | print_cat | cat_id:$cat_id state:open %}}");
        return;
    }

  }
?>