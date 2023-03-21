<?php
  class SystemMessages extends Model{
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
    protected static $outcome_messages = array('success'=>array(),'err'=>array());
    protected static $income_messages = array('isset'=>false,'messages'=>null);
    public static function add_success_message($message){
		  self::add_message('success',$message);
    }

    public static function add_err_message($message){
		  self::add_message('err',$message);
    }

    protected static function add_message($message_type,$message){
      //echo "adding    ". $message_type."==".$message."<br/>";
        self::$outcome_messages[$message_type][] = $message;
        session__set('system_'.$message_type."_messages",self::$outcome_messages[$message_type]);
        
    }

    public static function get_all(){
      if(self::$income_messages['isset']){
        return self::$income_messages['messages'];
      }
      else{
        $income_messages = array('success'=>array(),'err'=>array());
        foreach($income_messages as $key=>$val){
          $message_group = self::get_messages_from_session($key);
          if($message_group){
            $income_messages[$key] = $message_group;
          }
        }
      }
      self::$income_messages['isset'] = true;
      self::$income_messages['messages'] = $income_messages;
      return $income_messages;
    }

    protected static function get_messages_from_session($message_type){
      $session_param = "system_".$message_type."_messages";
      if(session__isset($session_param)){
        $session_val = session__get($session_param);
        session__unset($session_param);
        return $session_val;
      }
      else{
        return false;
      }
    }
	
  }
?>