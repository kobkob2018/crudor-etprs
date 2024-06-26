<?php
  class CheckController extends CrudController{
    
    public function arr_js(){
      $arr = array(
        "campaignid"=>array(
          "key"=>'cat_id',
          "values"=>array(
            "81"=>"sting",
            "83"=>"bezek",
            "348"=>"kobicustom",
          )
        ),
        "campaignpass"=>array(
          "key"=>'cat_id',
          "values"=>array(
            "81"=>"pass81",
            "83"=>"pass83",
            "348"=>"pass348",
          )
        ),
      );
      print(json_encode($arr));
      exit();
    }

    protected function setlog(){
      $this->set_layout("blank");
      $txt = "\n";
      foreach($_POST as $key=>$val){
        $txt.="$key: $val\n\n";
      }
      Helper::add_log("api_log3.txt",$txt);
    }

    protected function check(){
        $this->set_layout("blank");
        $api_key = get_config("curl_key");
        $headers = getallheaders();
        if($headers['authorization'] != "Bearer $api_key"){
            exit("permission denied - code 203");
        }

        if($_SERVER['REMOTE_ADDR'] != get_config('server_ip')){
            exit("permission denied - code 203");
        }       

        $request_body = file_get_contents('php://input');
        $request_arr = json_decode($request_body,true);
        
        $return_array = $this->init_form_data($request_arr);
        $lead_info = array(
          'full_name'=>$request_arr['full_name'],
          'phone'=>$request_arr['phone'],
          'city_id'=>$request_arr['city_id'],
          'cat_id'=>$request_arr['cat_id'],
          'referrer'=>'whatsapp',
          'email'=>'no-email',
          'extra_info'=>'',
          'note'=>'',
        );
        $return_array = $this->call_module("biz_request","enter_lead_by_api",array('return_array'=>$return_array,'lead_info'=>$lead_info));

        print_r_help(($return_array));
       // Helper::add_log("check_log.txt","\nHi now is ".$log);
        //echo nl2br($log);
        return;
    }


    protected function test_set(){
      $phone = "+972542393397";
      print_help($phone,'first');
      $phone = $this->replace_needle_with_0($phone,'972');
      print_help($phone,'second');
      $phone = $this->replace_needle_with_0($phone,'+972');
      print_help($phone,'third');
      exit();
    }

    protected function replace_needle_with_0($phone,$needle){
      Helper::add_log('needle_phones.txt',"\nthe check file:".$phone,"\n\n");
      $replace = '0';

      $pos = strpos($phone, $needle);
      if ($pos === 0) {
          $phone = substr_replace($phone, $replace, $pos, strlen($needle));
      } 
      return $phone;       
    }


    public function init_form_data($request_arr){
        
      $return_array = array(
          'state'=>'waiting',
          'success'=>true
      );  
      if(!isset($request_arr['form_id'])){
          $return_array['success'] = false;
          $return_array['error'] = array('msg'=>'missing form id');
          return $return_array;
      }
      $form_info = siteBiz_forms::get_by_id($request_arr['form_id']);


      $input_remove = $form_info['input_remove'];
      $input_remove_arr = explode(",",$input_remove);
      
      foreach($input_remove_arr as $remove_input){
          $input_remove_arr[] = trim($remove_input);
      }
      $form_info['input_remove_arr'] = $input_remove_arr;
      $this->data['form_info'] = $form_info;

      return $return_array;
  }


  }
?>