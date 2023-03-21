<?php
  class Helper {
    public static function eazy_index_arr_by($param_name,$arr_to_index,$param_of_value = '*'){
      $index_arr = array();
      foreach($arr_to_index as $key=>$value_arr){
        if($param_of_value == '*'){
          $value = $value_arr;
          if(is_array($value)){
            $value['key_index'] = $key;
          }
        }
        else{
          $value = $value_arr[$param_of_value];
        }
        $index_arr[$value_arr[$param_name]] = $value;
      }
      return $index_arr;
    }

    public static function eazy_index_switch_arr($arr_to_index){
      $index_arr = array();
      foreach($arr_to_index as $key=>$val){
        $index_arr[$val] = $key;
      }
      return $index_arr;
    }

    public static function user_is($needed_roll,$user,$work_on_site = false){
      $user_is = 'logout';


      $user_roll_list = User_rolls::get_list();
      $login_user_roll_hirachy = self::eazy_index_arr_by('level',$user_roll_list);
      $login_user_roll_hirachy[100] = array(
        'level'=>'100',
        'str_identifier'=>'login'
      );

      if($user){
        $user_is = $login_user_roll_hirachy[$user['roll']]['str_identifier'];
        if($needed_roll == 'logout'){
          return false;
        }
      }
      else{
        //return if is user loged out or not
        return ($user_is == $needed_roll);
      }

      //now start check hirarchy
      if($work_on_site){
        $user_is = $work_on_site['admin_roll'];
      }

      $exepted_rolls = array();
      foreach($login_user_roll_hirachy as $roll_arr){
        $roll =  $roll_arr['str_identifier'];
        $exepted_rolls[] = $roll;
        if($roll == $needed_roll ){
          break;
        }
      }

      foreach($exepted_rolls as $roll){
        if($roll == $user_is){
          return true;
        }
      }
      return false;
      
    } 
  
    public static function send_sms($phone,$msg){
      $msg = urlencode($msg);
      $micropay_url = get_config('micropay_url');
      $micropay_url = str_replace("{{phone}}", $phone, $micropay_url);
      $micropay_url = str_replace("{{msg}}", $msg, $micropay_url);
      $no_answer_url = get_config('base_url')."/master_admin/micropay_sms_result/handle/";
      $micropay_url = str_replace("{{no_answer_url}}", $no_answer_url, $micropay_url);
      $curlSend = curl_init(); 
      curl_setopt($curlSend, CURLOPT_URL, $micropay_url); 
      curl_setopt($curlSend, CURLOPT_RETURNTRANSFER, 1); 
      $curlResult = curl_exec ($curlSend); 
      curl_close ($curlSend); 
      return $curlResult;
    }

    public static function send_email($email_to, $email_title,$email_content){
      $email_sender = get_config('email_sender'); 
      $email_sender_name = get_config('email_sender_name');
      // Set content-type header for sending HTML email 
      $headers = "MIME-Version: 1.0" . "\r\n"; 
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
      
      // Additional headers 
      $headers .= 'From: '.$email_sender_name.'<'.$email_sender.'>' . "\r\n"; 
      mail($email_to,$email_title,$email_content,$headers);
      
    }

  }
?>
