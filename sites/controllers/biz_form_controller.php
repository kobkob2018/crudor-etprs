<?php
//http://love.com/biz_form/submit_request/?form_id=4&submit_request=1&biz[cat_id]=52&biz[full_name]=demo_post2&biz[phone]=098765432&biz[email]=no-mail&biz[city]=6&cat_tree[0]=47&cat_tree[1]=52
  class Biz_formController extends CrudController{
    public $add_models = array("sitePages","biz_categories","siteBiz_forms","cities","siteUser_cat_city");

    protected function handle_access($action){
        if(get_config('biz_forms_on') != '1'){
            $this->set_layout("blank");
            exit();
        }
        return parent::handle_access(($action));
    }

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function special_access_for_unlimited_requests(){
        session__set('biz_unlimited_count','1');
        SystemMessages::add_success_message("OK! NOW YOU CAN FILL FORMS WITHOUT LIMIT");
    }

    public function remove_access_for_unlimited_requests(){
        session__unset('biz_unlimited_count');
        session__unset('biz_request_count');
        SystemMessages::add_success_message("OK! NOW YOU CAN NOT !!! FILL FORMS WITHOUT LIMIT");
    }

    public function fetch(){

        $return_array = $this->init_form_data();
        if(!$this->check_continue($return_array)){
            
            return $this->print_json_page($return_array);
        }

        $return_array = $this->get_cat_children_select($return_array);
        if(!$this->check_continue($return_array)){
            return $this->print_json_page($return_array);
        }
        if(!$return_array['have_children']){
            $return_array = $this->get_cat_final_fields($return_array);
        }
        return $this->print_json_page($return_array);
    }

    public function init_form_data(){
        
        $return_array = array(
            'state'=>'waiting',
            'success'=>true
        );  
        if(!isset($_REQUEST['form_id'])){
            $return_array['success'] = false;
            $return_array['error'] = array('msg'=>'missing form id');
            return $return_array;
        }
        $form_info = siteBiz_forms::get_by_id($_REQUEST['form_id']);


        $input_remove = $form_info['input_remove'];
        $input_remove_arr = explode(",",$input_remove);
        
        foreach($input_remove_arr as $remove_input){
            $input_remove_arr[] = trim($remove_input);
        }
        $form_info['input_remove_arr'] = $input_remove_arr;
        $this->data['form_info'] = $form_info;

        return $return_array;
    }

    public function check_continue($return_array){
        if($return_array['success'] != true){
            return false;
        }
        return true;
    }


    public function demo_request(){
        $form_id = '1';
        $full_name = "kobi check";
        $email = "stam_mail@f.com";
        $phone = "054232323";
        $city_arr = array('1','2','3','4','5','6','7','8','9','10','32','46','47','48','49');
        $note = "just a note";
        $cat_id_arr = array('53','57','58','52','54','55','56');

        $city_id = $city_arr[rand(0,(count($city_arr) - 1))];
        $cat_id = $cat_id_arr[rand(0,(count($cat_id_arr) - 1 ))];
        $phone_ext = rand(100,999);
        $phone = $phone.$phone_ext;
        $full_name = $full_name.$phone_ext;
        $_REQUEST['form_id'] = $form_id;
        $_REQUEST['biz'] = array(
            'full_name'=>$full_name,
            'email'=>$email,
            'phone'=>$phone,
            'note'=>$note,
            'city'=>$city_id,
            'cat_id'=>$cat_id,
        );
        $this->data['let_phone_go'] = true;
        $this->submit_request();
    }

    public function submit_request(){
        
        $return_array = $this->init_form_data();
        
        if(!$this->check_continue($return_array)){  
            return $this->print_json_page($return_array);
        }

        if(!isset($_REQUEST['biz'])){
            $return_array['success'] = false;
            $return_array['error'] = array('msg'=>'empty form');
            return $this->print_json_page($return_array);
        }

        if($_REQUEST['biz']['full_name'] == 'demo_post'){
            return $this->init_post_demo_url($return_array);
        } 
        $return_array = $this->call_module("biz_request","enter_lead",array('return_array'=>$return_array));
        
        return $this->print_json_page($return_array);
    }

    public function init_post_demo_url($return_array){
        $str = "";
        foreach($_REQUEST as $key=>$val){
            if(is_array($val)){
                foreach($val as $k=>$v){
                    $str .= "&$key"."[".$k."]=$v";
                }
            }
            else{
                $str .= "&$key=$val";
            }
        }
        $str = outer_url("biz_form/submit_request/?v=1$str");
        $return_array['success'] = false;
        $return_array['error'] = array('msg'=>"take this:  $str");
        return $this->print_json_page($return_array);
    }

    public function get_cat_final_fields($return_array){
        $parent_tree = Biz_categories::get_item_parents_tree($return_array['cat_id'],'*');
        
        $add_email_to_form = false;
        foreach($parent_tree as $cat){
            //cascading default from top to bottom
            $add_email_to_form = $cat['add_email_to_form'];
        }
        $allowed_cities = SiteUser_cat_city::get_cat_city_assign($return_array['cat_id']);
        
        if(empty($allowed_cities)){
            $city_list = Cities::get_flat_select_city_options();
        }
        else{
            $city_list = Cities::get_filtered_flat_select_city_options($allowed_cities);
        }
        $allowed_city_id_arr = array();
        foreach($city_list as $city){
            $allowed_city_id_arr[] = $city['id'];
        }
        
        $extra_fields = array();
        foreach($parent_tree as $cat){
            if($cat['extra_fields'] != ''){
                $extra_fields[] = $cat['extra_fields'];
            }
        }
        $info = array(
            'parent_tree'=>$parent_tree,
            'extra_fields'=>$extra_fields
        );
        $return_array['html'] = $this->include_ob_view('biz_form/fetch_cat_extra_fields.php',$info);
        $return_array['state'] = 'ready';
        $return_array['submit_url'] = inner_url('biz_form/submit_request/?form_id='.$this->data['form_info']['id']);
        $return_array['allowed_cities'] = $allowed_city_id_arr;
        $return_array['add_email_to_form'] = $add_email_to_form;
        return $return_array;
    }

    public function get_cat_children_select($return_array){
        
        if(!isset($_REQUEST['cat_id'])){
            $return_array['success'] = false;
            $return_array['error'] = array('msg'=>__tr("Category was not selected"));
            return $return_array;
        }

        $cat_id = $_REQUEST['cat_id'];
        $return_array['cat_id'] = $cat_id;
        $return_array['have_children'] = false;
        $filter_arr = array('active'=>'1', 'visible'=>'1');
        $payload = array('order_by'=>'priority, label');
        $cat_children = Biz_categories::get_children_list_of($cat_id,'id,label,parent',$filter_arr,$payload);
        if(!$cat_children){
            return $return_array;
        }

        $info = array(
            'cat_id'=>$cat_id,
            'children'=>$cat_children,
            'form_info'=>$this->data['form_info']
        );

        $return_array['have_children'] = true;
        $return_array['html'] = $this->include_ob_view('biz_form/fetch_cat_select.php',$info);
        return $return_array;
    }

  }
?>