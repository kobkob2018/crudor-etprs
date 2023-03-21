<?php
  class Global_settings extends TableModel{

    protected static $settings = false;
    protected static $main_table = 'global_settings';

    public static function get(){
        if(!self::$settings){
            self::$settings = self::get_settings_from_db();
        }
        return self::$settings;
    }

    protected static function get_settings_from_db(){
      $settings_arr = self::get_list();
      $settings = array();
      foreach($settings_arr as $set){
        $param_name = $set['param_name'];
        $val_type = $set['val_type']."_val";
        $value = false;
        if(isset($set[$val_type])){
          $value = $set[$val_type];
        }
        $settings[$param_name] = $value;
      }
      return $settings;
    }

    protected static $field_types = array(
      'text'=>array(
        'type'=>'textbox',
        'css_class'=>'small-text'
      ),
      'varchar'=>array(
        'type'=>'text',
      ),
      'longvarchar'=>array(
        'type'=>'text',
      ),
      'int'=>array(
        'type'=>'text',
      ),
      'int'=>array(
        'type'=>'text',
      ),
      'tinyint'=>array(
        'type'=>'select',
        'options'=>array(
            array('value'=>'0', 'title'=>'לא'),
            array('value'=>'1', 'title'=>'כן')
        ),
      ),
    );

    public static function setup_kv_field_collection(){
      $settings_arr = self::get_list();
      $fields_collection = array();
      foreach($settings_arr as $set){
        $field = self::$field_types[$set['val_type']];
        $field['label'] = $set['label'];
        $val_type = $set['val_type']."_val";
        $value = false;
        if(isset($set[$val_type])){
          $value = $set[$val_type];
        }
        $field['default'] = $value;
        $fields_collection[$set['param_name']] = $field;
      }
      return self::setup_field_collection($fields_collection);
    }

    public static function update_kv_params($update_values){
      $settings_arr = self::get_list();
      $db = Db::getInstance();
      foreach($settings_arr as $set){
        if(isset($update_values[$set['param_name']])){
          $val_type = $set['val_type']."_val";
          $param_name = $set['param_name'];
          $value = $update_values[$param_name];
          $execute_arr = array(
            'val'=>$value,
            'param_name'=>$param_name
          );
          
          $sql = "UPDATE global_settings SET $val_type = :val WHERE param_name = :param_name";	
          $req = $db->prepare($sql);
          $req->execute($execute_arr);
        }
      }
    }

    public static function add_new_kv_param($param_data){
      $db = Db::getInstance();

      $row_data = array(
        'param_name'=>$param_data['param_name'],
        'label'=>$param_data['label'],
        'val_type'=>$param_data['val_type'],
      );
      
      return self::create($row_data);
    }

  }
?>