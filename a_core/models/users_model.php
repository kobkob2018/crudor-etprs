<?php
  class Users extends TableModel{

    protected static $main_table = 'users';

    protected static $auto_delete_from_attached_tables = array(
      'user_city'=>array(
          'table'=>'user_city',
          'id_key'=>'user_id'
      ),
      'user_cat'=>array(
          'table'=>'user_cat',
          'id_key'=>'user_id'
      ),
      'user_cat_city'=>array(
          'table'=>'user_cat_city',
          'id_key'=>'user_id'
      ),
    ); 

    public static function get_loged_in_user() {
      return UserLogin::get_user();
    }

    public static function get_by_id($user_id, $select_params = "*") {
      $filter_arr = array('id'=>$user_id);
      return self::simple_find($filter_arr, $select_params);
    }

    public static $fields_collection = array(
      'username'=>array(
          'label'=>'שם משתמש',
          'type'=>'text',
          'validation'=>'required'
      ),

      'password'=>array(
        'label'=>'סיסמא',
        'edit_tip'=>'השאר ריק אם אינך רוצה לשנות',
        'type'=>'password',
        'custom_validation'=>'validate_by_password'
      ),

      'full_name'=>array(
        'label'=>'שם מלא',
        'type'=>'text',
        'validation'=>'required'
      ),

      'biz_name'=>array(
        'label'=>'שם עסק',
        'type'=>'text'
      ),

      'email'=>array(
        'label'=>'אימייל',
        'type'=>'text',
        'validation'=>'required, email'
      ),

      'phone'=>array(
        'label'=>'טלפון',
        'type'=>'text',
        'validation'=>'phone'
      ),

      'active'=>array(
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
        'label'=>'תפקיד משתמש',
        'type'=>'select',
        'select_blank'=>array('value'=>'0','label'=>'---'),
        'options_method'=>array('model'=>'User_rolls','method'=>'get_select_options')
      ),

      'address'=>array(
        'label'=>'כתובת',
        'type'=>'text'
      ),
      'sex'=>array(
        'label'=>'מין',
        'type'=>'select',
        'options'=>array(
            array('value'=>'1', 'title'=>'זכר'),
            array('value'=>'2', 'title'=>'נקבה')
        )
      ),

      'birth_date'=>array(
        'label'=>'תאריך לידה',
        'type'=>'date',
        'validation'=>'date'
      ),

    );  

  }
?>