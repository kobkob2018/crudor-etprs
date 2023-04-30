<?php
  class Product_cat extends TableModel{

    protected static $main_table = 'product_cat';
    protected static $select_options = false;

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'שם התיקייה',
            'type'=>'text',
            'validation'=>'required'
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
    );

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
  
}
?>