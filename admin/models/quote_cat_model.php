<?php
  class Quote_cat extends TableModel{

    protected static $main_table = 'quote_cat';
    protected static $select_options = false;

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        )
    );

    public static $cat_delete_fields_collection = array(
        'move_quotes_to'=>array(
            'label'=>'העבר הצעות מחיר לתיקייה',
            'type'=>'select',
            'default'=>'0',
            'options_method'=>array('model'=>'quote_cat','method'=>'get_select_cat_options'),
            'validation'=>'required'
        )
    );

    public static function get_fields_collection_for_cat_delete($delete_cat_id){
        $cat_options = $cat_options = array();
        $execute_arr = array(
            'delete_cat_id'=>$delete_cat_id
        );
        $sql = "SELECT id, label FROM quote_cat WHERE id != :delete_cat_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $cat_list = $req->fetchAll();
        foreach($cat_list as $cat){
            $cat_options[] = array('value'=>$cat['id'],'title'=>$cat['label']);
        }
        $cat_delete_fields_collection = array(
            'move_quotes_to'=>array(
                'label'=>'העבר הצעות מחיר לתיקייה',
                'type'=>'select',
                'default'=>'0',
                'options'=>$cat_options,
                'validation'=>'required'
            )
        );
        return $cat_delete_fields_collection;
    }

    public static function get_select_cat_options(){
        if(self::$select_options){
            return self::$select_options;
        }
        $cat_list = self::get_list(array(),'id, label');
        $return_list = array();
        foreach($cat_list as $cat){
            $return_list[] = array('value'=>$cat['id'],'title'=>$cat['label']);
        }
        self::$select_options = $return_list;
        return self::$select_options;
    }

    public static function get_select_cat_options_without($cat_id){
        if(self::$select_options){
            return self::$select_options;
        }
        $cat_list = self::get_list(array(),'id, label');
        $return_list = array();
        foreach($cat_list as $cat){
            $return_list[] = array('value'=>$cat['id'],'title'=>$cat['label']);
        }
        self::$select_options = $return_list;
        return self::$select_options;
    }    

    public static function move_quotes_from_cat_to($cat_from,$cat_to){
        $execute_arr = array(
            'cat_from'=>$cat_from,
            'cat_to'=>$cat_to
        );
        $sql = "UPDATE quotes SET cat_id = :cat_to WHERE cat_id = :cat_from";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
    }
}
?>