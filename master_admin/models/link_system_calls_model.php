<?php

//this one is used at the cities controller
//in the cats controller the cat_city model is used
  class Link_system_calls extends Model{

    private static $link_db = NULL;

    private static $link_tables = array(
      'ANSWERED'=>'in1331',
      'NO ANSWER'=>'abdn1331',
      'MESSEGE'=>'cb1331',
    );

    protected static function getLinkDb() {
      if (!isset(self::$link_db)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$link_db = new PDO('mysql:host='.get_config('link_host').'; port='.get_config('link_port').'; dbname='.get_config('link_db').'', get_config('link_user'), get_config('link_pass'), $pdo_options);
        
        //if not working try without port: 
        //self::$link_db = new PDO('mysql:host='.get_config('link_host').';  dbname='.get_config('link_db').'', get_config('link_user'), get_config('link_pass'), $pdo_options);
      }
      return self::$link_db;
    }

    public static function get_new_calls(){
      
      $new_calls_arr = array();

      $answered_calls = self::fetch_calls_from_link_by_status("ANSWERED");
      $no_answer_calls = self::fetch_calls_from_link_by_status("NO ANSWER");
      $msg_calls = self::fetch_calls_from_link_by_status("MESSEGE");

      foreach($answered_calls as $call){
        $new_calls_arr[$call['CallId']] = $call;
      }

      foreach($no_answer_calls as $call){
        //some fix to the lead details...
        $call['src'] = $call['callerid'];
        $call['dst'] = $call['did'];
        $new_calls_arr[$call['CallId']] = $call;
      }
      
      foreach($msg_calls as $call){
        $call['src'] = $call['callerid'];
        $call['dst'] = $call['did'];
        $new_calls_arr[$call['CallId']] = $call;
      }

      $missing_dst = array();
      $tracking_phones = array();

      foreach ($new_calls_arr as $call) {
        $user_phone = self::get_user_phone_by_call($call);
        //changes from old db:

        // bill_normal: lead_bill

        $dst = $call['dst'];

        if (!$user_phone) {
          if(isset($missing_dst[$dst])){
            $missing_dst[$dst] = $missing_dst[$dst]+1;
          }
          else{
            $missing_dst[$dst] = 1;
          }
        }
        else{
          $call_data = array(
            'user_id' => $user_phone['user_id'],
            'call_from' => $call['src'],
            'call_to' => $call['dst'],
            'did' => $call['did'],
            'answer' => $call['answer'],
            'sms_send' => $call['sms_sent'],
            'call_date' => $call['time'],
            'billsec' => $call['duration'],
            'uniqueid'  => '0',
            'link_sys_id'  => $call['CallId'],
            'link_sys_identity' => $call['uniqueid'],
            'recordingfile'  => $call['filename'],          
            'extra' => ''
          );
          if($call['answer'] == "MESSEGE"){
            $call_data['extra'] = $call['number'];  
          }

          $call_id = self::simple_create_by_table_name($call_data,'user_phone_calls');

          $call_data['id'] = $call_id;

          $times_called = self::set_times_called_to_call($call_data,$call_id);
          $tracked = $times_called == '0' ? '1' : '0';



          $lead_data = array(
            'user_id'=>$user_phone['user_id'],
            'date_in'=>$call['time'],
            'open_state'=>'1',
            'phone'=>$call['src'],
            'request_id'=>'0',
            'resource'=>'phone',
            'billed'=>'1',
            'duplicate_id'=>'0',
            'phone_id'=>$call_id,
            'campaign_type'=>$user_phone['campaign_type'],
            'campaign_name'=>$user_phone['campaign_name'],  
          );


          $lead_data = self::handle_lead_billing_and_duplicates($lead_data,$user_phone);

          $lead_id = self::simple_create_by_table_name($lead_data,'user_leads');
          
          $lead_data['id'] = $lead_id;

          $tracking_phones[] = array("p"=>$call_data['did'],"r"=>$call_id,"c"=>$lead_id,"t"=>$tracked,"tc"=>$times_called);

          self::handle_phone_api_send($user_phone,$lead_data,$call_data);



          self::handle_return_sms($call,$user_phone);

        }

      }

      self::handle_missing_user_phones($missing_dst);

      self::handle_tracking_phones($tracking_phones);

      exit("ok to here");
    }

    protected function handle_tracking_phones($tracking_phones){
      $c_tracking_on = Global_settings::get()['c_tracking_on'];
      if($c_tracking_on){
        return;
      }
      $tracking_phones_json = json_encode($tracking_phones);
	
      $track_params = "?phones_tracked=".$tracking_phones_json;
      $master_url = get_config('master_url');


      //todo see what they do in "https://ilbiz.co.il/c_tracking/"...
      $track_url = $master_url."/c_tracking/track/".$track_params;  
      $track_ch = curl_init(); 
      curl_setopt( $track_ch, CURLOPT_URL,$track_url ); 
      curl_setopt($track_ch, CURLOPT_HEADER, 0);
      curl_setopt( $track_ch, CURLOPT_POST, 1 ); 
      curl_setopt( $track_ch, CURLOPT_POSTFIELDS, $track_params ); 
      curl_setopt($track_ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($track_ch, CURLOPT_FOLLOWLOCATION, TRUE);
      $resualt = curl_exec ($track_ch); 
      //mail("yacov.avr@gmail.com","check phones_37 ",$msg_to_email."\n\n\n\n".$tracking_phones_json."----".$resualt);  
      curl_close ($track_ch);
    }

    protected function handle_phone_api_send($user_phone,$lead_data,$call_data){
      $db = Db::getInstance();
      $sql = "SELECT * FROM user_phone_api WHERE phone_id = :phone_id";
      $execute_arr = array('phone_id'=>$user_phone['did']);  
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $api_sends = $req->fetchAll();
      if(!$api_sends){
        return;
      }
      foreach($api_sends as $api_send){
        $api_url = $api_send['url'];
        foreach($lead_data as $key=>$val){
          $api_url = str_replace("{$key}",$val,$api_url);
        }
        foreach($call_data as $key=>$val){
          $api_url = str_replace("{$key}",$val,$api_url);
        }


        //break the url and params for the curl, remove the first ? from params 
        //but if some parameter has a ? sign and we explode by mistake so return it
        $url_arr = explode("?",$api_send_url);
			  $url = $url_arr[0];
			  $params = "";
			  for($i=1;$i<count($url_arr);$i++){
				  if($i!=1){
					  $params.="?";
				  }
				  $params.=$url_arr[$i];
			  }

        $ch = curl_init(); 
        curl_setopt( $ch, CURLOPT_URL,$url ); 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_POST, 1 ); 
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params ); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $resualt = curl_exec ($ch); 
        
        curl_close ($ch);

      }
    }

    protected static function set_times_called_to_call($call_data,$call_id){
      $db = Db::getInstance();
      $sql = "SELECT COUNT(id) as times_called_count FROM user_phone_calls WHERE did= :did AND call_from = :call_from AND id != :call_id";
      $execute_arr = array('did'=>$call_data['did'], 'call_from'=>$call_data['call_from'], 'call_id'=>$call_id);  
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $count_result = $req->fetch();
      $times_called = '0';
      if($count_result && $count_result['times_called_count'] != '0'){
        $times_called = $count_result['times_called_count'];
        $sql = "UPDATE user_phone_calls SET times_called = :times_called WHERE id = :call_id";
        $execute_arr = array('times_called'=>$times_called, 'call_id'=>$call_id);  
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        
      }
      return $times_called;
    }

    protected static function handle_missing_user_phones($missing_dst){
      if(!empty($missing_dst)){
        $missing_str = "phones not belong to customerts: \n";
        foreach($missing_dst as $key=>$counter){
          $missing_str.= "\n missing: ".$key."($counter)";
        }
        Helper::send_email("ilan@il-biz.com","phones without user",$missing_str);
      }
    }

    protected static function handle_return_sms($call,$user_phone){
      if($call['answer'] == "NO ANSWER" && $user_phone['misscall_sms_return'] == '1'){
        $phone = $call['src'];
        $msg = $user_phone['misscall_sms'];
        $smsResult = Helper::send_sms($phone,$msg);
        if(strpos($smsResult, "OK") === false){
          // todo: send some note to ilan
        }
      }
    
      if($call['answer'] == "ANSWERED" && $user_phone['aftercall_sms_send'] == '1'){
        $phone = $call['src'];
        $msg = $user_phone['aftercall_sms'];
        $smsResult = Helper::send_sms($phone,$msg);
        if(strpos($smsResult, "OK") === false){
          // todo: send some note to ilan
        }
      }
    }

    protected function handle_lead_billing_and_duplicates($lead_data,$user_phone){
      $db = Db::getInstance();
      $sql = "SELECT id, duplicate_id FROM user_leads WHERE phone = :phone AND lead_billed = 1 AND user_id = :user_id AND date_in > (CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE)) LIMIT 1";
      $execute_arr = array('phone'=>$lead_data['phone'], 'user_id'=>$lead_data['user_id']);  
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $duplicated_lead = $req->fetch();

      if($duplicated_lead){
        $lead_data['billed'] = '0';
        $duplicate_id = $duplicated_lead['id'];
        if($duplicated_lead['duplicate_id'] != '' && $duplicated_lead['duplicate_id'] != '0'){
          $duplicate_id = $duplicated_lead['duplicate_id'];
        }
        $lead_data['duplicate_id'] = $duplicate_id;
      }
      elseif($user_phone['lead_bill'] == '0'){
        $lead_data['billed'] = '0';
      }
      else{
        //if phone cbilled as lead, remove 1 lead_credit from user_lead_settings
        $sql = "UPDATE user_lead_settings SET lead_credit = lead_credit - 1 WHERE user_id = :user_id";
        $execute_arr = array('user_id'=>$lead_data['user_id']);
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        
      } 
      return $lead_data;
    }

    protected static function get_user_phone_by_call($call){
      $db = Db::getInstance();
      $did = isset($call['did'])? $call['did'] : "" ;
      if(!$did == ''){
        $did = '0';
      }

      $execute_arr = array('number_find'=>$did);
      $sql = "SELECT * FROM user_phones WHERE number = :number_find LIMIT 1";
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $user_phone = $req->fetch();
      if($user_phone){
        return $user_phone;
      }

      //if user phone not found, try again with the dst param
      if(!isset($call['dst']) || $call['dst'] == ''){
        return false;
      }
      $execute_arr['number_find'] =  $call['dst'];

      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $user_phone = $req->fetch();
      return $user_phone;

    }

    protected static function fetch_calls_from_link_by_status($call_status){
      
      $last_lead_id = self::get_last_call_fetched_by_status($call_status);
      $link_table = self::$link_tables[$call_status];
      $link_db = self::getLinkDb();

      //try to limit selection to last 2 days if our db is new with no records so we are not overfloated with too much db
      $sql = "select * from $link_table WHERE CallId > $last_lead_id AND (time > now() - interval 2 day)";
      $req = $link_db->prepare($sql);
      $req->execute();
      $result = $req->fetchAll();
      if(!$result){
        return array();
      }
      foreach($result as $call_key=>$call){
        $result[$call_key]['answer'] = $call_status; 
      }
      return $result;
    }

    protected static function get_last_call_fetched_by_status($call_status){
        $db = Db::getInstance();		
        $sql = "SELECT link_sys_id FROM user_phone_calls WHERE answer = '".$call_status."' ORDER BY link_sys_id desc LIMIT 1";
        $req = $db->prepare($sql);
        $req->execute();
        $last_lead_data = $req->fetch();
        if(!$last_lead_data){
          return '0';
        }
        return $last_lead_data['link_sys_id'];
    }
}
?>