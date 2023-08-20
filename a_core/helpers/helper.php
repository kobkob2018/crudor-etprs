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
      
      if(!in_array($needed_roll,$exepted_rolls)){
        return false;
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
      if(get_config('mode') == 'dev'){
        return self::send_email_dev($email_to, $email_title,$email_content);
      }
      require_once('a_core/helpers/smtp_handler.php');
      $email_sender = get_config('email_sender'); 
      $email_sender_name = get_config('email_sender_name'); 
      send_email_with_smtp($email_sender,$email_sender_name,$email_to,$email_title,$email_content);      
    }

    public static function send_email_dev($email_to, $email_title,$email_content){
      $email_sender = get_config('email_sender'); 
      $email_sender_name = get_config('email_sender_name');
      // Set content-type header for sending HTML email 
      $headers = "MIME-Version: 1.0" . "\r\n"; 
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
      
      // Additional headers 
      $headers .= 'From: '.$email_sender_name.'<'.$email_sender.'>' . "\r\n"; 
      mail($email_to,$email_title,$email_content,$headers);
      
    }

    public static function add_log($log_file,$log_text){
      if(!is_dir('assets_s')){
        $oldumask = umask(0) ;
        $mkdir = @mkdir( 'assets_s', 0755 ) ;
        umask( $oldumask ) ;
      }
      if(!is_dir('assets_s/logs')){
          $oldumask = umask(0) ;
          $mkdir = @mkdir( 'assets_s/logs', 0755 ) ;
          umask( $oldumask ) ;
      }
      $log_filename = 'assets_s/logs/'.$log_file;

      file_put_contents($log_filename, $log_text, FILE_APPEND);
    }

    public static function clear_log($log_file){
      self::add_log($log_file,"clearing now");

      $log_filename = 'assets_s/logs/'.$log_file;

      file_put_contents($log_filename, "");
    }    

    public static function array_to_csv_download2($data, $filename = "export.csv", $delimiter=";") {
      // open raw memory as file so no temp files needed, you might run out of memory though
      $f = fopen('php://memory', 'w'); 
      // loop over the input array
      //UTF-16LE BOM
      fputs($f, chr(0xFF) . chr(0xFE));
      foreach ($data as $fields) {
        $out = '';
        foreach ($fields as $k => $v){
          if($v){
            $fields[$k] = mb_convert_encoding($v, 'UTF-16LE', 'UTF-8');          
          }
        }
      
        // UTF-16LE tab
        $out = implode(chr(0x09).chr(0x00), $fields);
      
        // UTF-16LE new line
        fputs($f, $out.chr(0x0A).chr(0x00));
      }
      
      // reset the file pointer to the start of the file
      fseek($f, 0);
      // tell the browser it's going to be a csv file
      header('Content-Type: application/csv');
      // tell the browser we want to save it instead of displaying it
      header('Content-Disposition: attachment; filename="'.$filename.'";');
      // make php send the generated csv lines to the browser
      fpassthru($f);
  }
    public static function array_to_csv_download($data, $filename = "export.csv", $delimiter=";") {
        
      ob_clean();
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w'); 
        // loop over the input array
        
        foreach ($data as $line) fputcsv($f, $line);
        
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        fpassthru($f);
    }
  }
?>
