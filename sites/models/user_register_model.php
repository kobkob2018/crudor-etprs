<?php
  class User_register extends TableModel{

    protected static $main_table = 'user_register';

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
        'validation'=>'required, email',
        'custom_validation'=>'validate_by_email'
      ),

      'phone'=>array(
        'label'=>'טלפון',
        'type'=>'text',
        'validation'=>'required, phone'
      ),

      'address'=>array(
        'label'=>'כתובת',
        'type'=>'text'
      ),

      'city_name'=>array(
        'label'=>'שם עיר (לכתובת)',
        'type'=>'text'
      ),

      'sex'=>array(
        'label'=>'מין',
        'type'=>'select',
        'options'=>array(
            array('value'=>'1', 'title'=>'זכר'),
            array('value'=>'2', 'title'=>'נקבה')
        ),
        'validation'=>'required'
      ),

      'birth_date'=>array(
        'label'=>'תאריך לידה',
        'type'=>'date',
        'validation'=>'required, date'
      ),

    );  
  }
?>