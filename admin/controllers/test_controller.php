<?php
  class TestController extends CrudController{

    protected function handle_access($action){
      return true;
    }

    public function testi(){
      $this->include_view("portal_user\item_assign_label.php",array('item'=>array('test'=>'kob'),'global_info'=>array()));
    } 

    public $add_models = array("test");
    public function pixelize_me(){
      $this->set_layout("blank");
      header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_REFERER']);
      $label = $_REQUEST['label'];
      $add_arr = array('label'=>$label);
      Test::create($add_arr);
    }

    public function redirected_here(){
      
      echo "<h1>HA HA redirected</h1>";
      print_r_help($_REQUEST,"here are some params");
    }

    public function send_email_test(){
      

      $this->send_email('happyjopy@gmail.com',"שולח גם להאפיאת","<h2>Hi, מה מה מה email</h2><p>do you see?, is this good?</p>");
      echo "sending email now!";


      $this->send_email('yacov.avr@gmail.com',"שלום להאפי","<h2>Hi, מה מה מה email</h2><p>do you see?, is this good?</p>");
      echo "sending email now!";
      

    }

    public function send_sms_test(){
      $phone = '0542393397';
      $msg = 'שלום זוהי הודעה מאיי אל ביז';
      $curlResult = Helper::send_sms($phone,$msg);
      var_dump($curlResult);
    }

  }
?>