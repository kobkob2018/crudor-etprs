<?php
  class Site_users extends TableModel{

    protected static $main_table = 'user_sites';

    public static function get_site_users_list($site_id){
        $filter_arr = array('site_id'=>$site_id);
        return self::simple_get_list_by_table_name($filter_arr, 'user_sites');
    }

    public static $fields_collection = array(
        'user_id'=>array(
            'label'=>'שיוך למשתמש',
            'type'=>'select',
            'options_method'=>array('model'=>'siteUsers','method'=>'get_select_user_options'),
            'validation'=>'required',
        ),
        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'פעיל')
            ),
            'validation'=>'required'
        ),
        'roll'=>array(
            'label'=>'תפקיד',
            'type'=>'select',
            'default'=>'admin',
            'options_method'=>array(
                array('value'=>'writer', 'title'=>'כותב'),
                array('value'=>'admin', 'title'=>'מנהל'),
                array('value'=>'master_admin', 'title'=>'מנהל כל'),
            ),
            'validation'=>'required'
        ),       

    ); 

}
?>