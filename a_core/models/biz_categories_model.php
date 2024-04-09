<?php
  class Biz_categories extends TableModel{

    protected static $main_table = 'biz_categories';

    protected static $tree_singletons = array(); 
    public static function get_item_parents_tree($item_id, $select_params = "*"){
        $singleton_i_1 = $item_id;
        $singleton_i_2 = self::create_params_index_str($select_params);
        if(isset($tree_singletons[$singleton_i_1])){
            if(isset($tree_singletons[$singleton_i_1][$singleton_i_2])){
                return $tree_singletons[$singleton_i_1][$singleton_i_2];
            }
        }
        else{
            $tree_singletons[$singleton_i_1] = array();
        }
        $return_result = parent::get_item_parents_tree($item_id, $select_params);
        $tree_singletons[$singleton_i_1][$singleton_i_2] = $return_result;
        return $tree_singletons[$singleton_i_1][$singleton_i_2];
    }

    protected static function create_params_index_str($select_params){
        $params_str_arr = array();
        $params_arr = explode(",",$select_params);
        foreach($params_arr as $param){
            $params_str_arr[] = trim($param);
        }
        return implode("_",$params_str_arr);
    }

    protected static $auto_delete_from_attached_tables = array(
        'cat_city'=>array(
            'table'=>'cat_city',
            'id_key'=>'cat_id'
        ),
        'user_cat'=>array(
            'table'=>'user_cat',
            'id_key'=>'cat_id'
        ),
        'user_cat_city'=>array(
            'table'=>'user_cat_city',
            'id_key'=>'cat_id'
        ),
        'cat_whatsapp_terms'=>array(
            'table'=>'cat_whatsapp_terms',
            'id_key'=>'cat_id'
        ),
        
    );    

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'שם הקטגוריה',
            'type'=>'text',
            'validation'=>'required'
        ),
        'priority'=>array(
            'label'=>'מיקום',
            'type'=>'text',
            'default'=>'50',
            'validation'=>'required, int'
        ),
        'active'=>array(
            'label'=>'סטטוס פעיל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'visible'=>array(
            'label'=>'נראה באתר',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'cat_phone'=>array(
            'label'=>'טלפון לתצוגה',
            'type'=>'text'
        ),

        'unique_phone'=>array(
            'label'=>'טלפון ייחודי מזהה קטגוריה',
            'type'=>'text'
        ),

        'googleADSense'=>array(
            'label'=>'קמפיין גוגל(במקום טופס)',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),

        'use_parent_gas'=>array(
            'label'=>'השתמש בקמפיין של קטגוריית אב',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'extra_fields'=>array(
            'label'=>'הוספת שדות',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),

        'max_lead_send'=>array(
            'label'=>'מקסימום לידים לשליחה (0=ללא הגבלה)',
            'type'=>'text',
            'validation'=>'required, int',
            'default'=>'0',
        ),

        'add_email_to_form'=>array(
            'label'=>'הוסף שדה אימייל',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'show_whatsapp_button'=>array(
            'label'=>'הצג כפתור ווטסאפ',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
        'add_city_to_whatsap'=>array(
            'label'=>'בקש עיר בשיחת ווטסאפ',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
    );

    public static function find_matches_with($message_text){
        $message_text = "עורך דין";
        $matching_cats = array();
        $db = Db::getInstance();		
        $execute_arr = array('message_text'=>$message_text);
        $sql = "SELECT * FROM biz_categories WHERE MATCH(label) AGAINST(:message_text)";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);     
        $matching_cats_by_label = $req->fetchAll();
        if($matching_cats_by_label){
            foreach($matching_cats_by_label as $cat){
                $matching_cats[$cat['id']] = $cat;
            }
        }

        $sql = "SELECT * FROM biz_categories WHERE MATCH(search_terms) AGAINST(:message_text)";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);     
        $matching_cats_by_search = $req->fetchAll();
        if($matching_cats_by_search){
            foreach($matching_cats_by_search as $cat){
                $matching_cats[$cat['id']] = $cat;
            }
        }
        print_r_help($matching_cats);
        return $matching_cats;
    }
}
?>