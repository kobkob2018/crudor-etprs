<?php
  class Supplier_cubes extends TableModel{

    protected static $main_table = 'supplier_cubes';


    public static function get_select_supplier_options(){
        $db = Db::getInstance();
        $sql = "SELECT id, full_name FROM users order by full_name";
        $req = $db->prepare($sql);
        $sql_arr = array();
        $req->execute($sql_arr);
        $user_list = $req->fetchAll();
        $return_options = array();
        foreach($user_list as $user){
            $return_options[] = array('value'=>$user['id'],'title'=>$user['full_name']);
        }
        return $return_options;
    }

    public static function get_select_banner_options(){
        $db = Db::getInstance();
        $sql = "SELECT id, label FROM net_banners order by label";
        $req = $db->prepare($sql);
        $sql_arr = array();
        $req->execute($sql_arr);
        $banner_list = $req->fetchAll();
        $return_options = array();
        foreach($banner_list as $banner){
            $return_options[] = array('value'=>$banner['id'],'title'=>$banner['label']);
        }
        return $return_options;
    }

    public static function get_select_city_options(){
        $cities_tree = self::simple_get_item_offsprings_tree_by_table_name('0', 'cities',"id, parent,label",array(),array('order_by'=>'label'));

        $return_options = array();
        foreach($cities_tree[0]['children'] as $key=>$area){
            
            $return_options[] = array('value'=>$area['id'],'title'=>"--".$area['label']."--");
            if($area['has_children']){
                foreach($area['children'] as $city){
                    $return_options[] = array('value'=>$city['id'],'title'=>$city['label']);
                }
            }
        }
        return $return_options;
    }

    public static $fields_collection = array(

        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),

        'user_id'=>array(
            'label'=>'נותן שירות',
            'type'=>'select',
            'options_method'=>array('model'=>'supplier_cubes','method'=>'get_select_supplier_options'),
            'validation'=>'required',
            'custom_validation'=>'select_supplier_',
        ),      

        'banner_id'=>array(
            'label'=>'שיוך לבאנר',
            'type'=>'select',
            'options_method'=>array('model'=>'supplier_cubes','method'=>'get_select_banner_options')
        ),  

        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'בבדיקה'),
                array('value'=>'2', 'title'=>'אמין')
            )
        ),
        'link'=>array(
            'label'=>'לינק',
            'type'=>'text'
        ),  
        
        
        'city_id'=>array(
            'label'=>'שיוך לעיר',
            'type'=>'select',
            'options_method'=>array('model'=>'supplier_cubes','method'=>'get_select_city_options')
        ),

        'phone'=>array(
            'label'=>'טלפון שיוצג בקובייה',
            'type'=>'text'
        ), 

        'site_phone'=>array(
            'label'=>'טלפון שיוצג באתר',
            'type'=>'text'
        ), 

        'whatsapp_phone'=>array(
            'label'=>'טלפון לווטסאפ',
            'type'=>'text'
        ), 

        'whatsapp_text'=>array(
            'label'=>'טקסט לווטסאפ',
            'type'=>'text'
        ), 

        'activity_hours'=>array(
            'label'=>'שעות פעילות',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ), 
        

        'email'=>array(
            'label'=>'אימייל',
            'type'=>'text'
        ), 
        'address'=>array(
            'label'=>'כתובת',
            'type'=>'text'
        ),  

        'more_cities'=>array(
            'label'=>'עוד ערים',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),  
        'cube_image'=>array(
            'label'=>'תמונת לוגו',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'s_cubes',
            'name_file'=>'banner_{{row_id}}.{{ext}}'
        ),
    );
}
?>