<?php
  class User_rolls extends TableModel{

    protected static $main_table = 'user_rolls';
    protected static $select_opriotns_arr = array();

    public static function get_select_options(){
        if(isset(self::$select_opriotns_arr['user_rolls'])){
            return self::$select_opriotns_arr['user_rolls'];
        }

        $rolls_list = User_rolls::get_list();
        $return_options = array();
        foreach($rolls_list as $roll){
            $return_options[] = array('value'=>$roll['level'],'title'=>$roll['label']);
        }
        self::$select_opriotns_arr['user_rolls'] = $return_options;
        return $return_options;
    }

  }
?>