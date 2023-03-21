<?php
//only for loged out user
   class UserLoginController extends CrudController{  
   
	function __construct() {
        parent::__construct();
        $this->set_body('main');
    }

	protected function handle_access($action){
		switch ($action){
			case 'logout':
					return true;
				break;
			default:
				return $this->call_module(get_config('main_module'),'handle_access_loggedout_only');
			  break;
			
		  }
		
    }	

	public function loginSend(){
		
		$log_in_user = UserLogin::authenticate($_REQUEST['user_username'],$_REQUEST['user_pass']);
		if($log_in_user){
			$login_with_sms = Global_settings::get()['login_with_sms'];
			$login_trace = UserLogin::add_login_trace($log_in_user['id'],$login_with_sms);
			if($login_with_sms){
				$this->send_login_sms_code($log_in_user['phone'],$login_trace['sms_code']);
				SystemMessages::add_success_message("רק עוד שלב אחד");
				$this->redirect_to(outer_url("userLogin/login/"));
			}
			else{
				$this->end_login();
			}
		}
		else{			
			SystemMessages::add_err_message("שם המשתמש והסיסמה אינם תואמים"); 
		}		
	}

	public function smsLoginSend(){
		$log_in_user = UserLogin::authenticate_sms($_REQUEST['sms_code']);
		if(!$log_in_user){
			SystemMessages::add_err_message("קוד אינו תואם למה ששלחנו");
		}
		else{			
			$this->end_login();
		}
	}

	protected function end_login(){
		$go_to_page = outer_url('');
		if(session__isset('last_requested_url')){
			$go_to_page = session__get('last_requested_url');
			session__unset('last_requested_url');
		}
		
		$this->redirect_to($go_to_page);
		return;
	}

	public function forgotPasswordSend(){
		$log_in_user = UserLogin::authenticate_mail($_REQUEST['user_email']);
		if($log_in_user){
			$token_array = UserLogin::add_reset_password_token($log_in_user['id']);
			$this->data['forgot_password_user'] =  $log_in_user;
			$this->data['forgot_password_token'] =  $token_array;
			ob_start();
				$this->include_view('emails_send/forgotPasswordEmail.php');

			$email_content = ob_get_clean();
			$email_to = trim($log_in_user['email']);
			$email_title = "סיסמתך במערכת שיתוף לידים של אילביז";

			$this->send_email($email_to, $email_title, $email_content);

			SystemMessages::add_success_message("סיסמתך נשלחה אל כתובת המייל"); 
			$this->redirect_to(inner_url("userLogin/login/"));
		}
		else{
			SystemMessages::add_err_message("כתובת האימייל לא נמצאה במערכת");
		}		
	}

    public function resetPassword(){
		if(!(isset($_GET['row_id']) && isset($_GET['token']))){
			SystemMessages::add_err_message("פג תוקף האפשרות לאיפוס הסיסמה");
			$this->redirect_to(inner_url("userLogin/login/"));
			return;
		}

		$token_data = UserLogin::authenticate_password_token($_GET['row_id'],$_GET['token']);
		$token_data['row_id'] = $token_data['id'];
		session__set('reset_password_token',$token_data);
		$this->include_view('user/resetPassword.php');
    }

	public function resetPasswordSend(){
		$session_token = session__get('reset_password_token');
		if(!$session_token){
			SystemMessages::add_err_message("פג תוקף האפשרות לאיפוס הסיסמה -");
			$this->redirect_to(inner_url("userLogin/login/"));
			return;
		}
		$token_data = UserLogin::authenticate_password_token($session_token['row_id'],$session_token['token']);

		if(!$token_data){
			SystemMessages::add_err_message("פג תוקף האפשרות לאיפוס הסיסמה -- ");
			$this->redirect_to(inner_url("userLogin/login/"));
			return;
		}
		$new_pass_exist = false;
		if(isset($_REQUEST['usr'])){
			if(isset($_REQUEST['usr']['password']) && isset($_REQUEST['usr']['password_auth'])){
				$new_pass_exist = true;
			}
		}
		if(!$new_pass_exist){
			SystemMessages::add_err_message("אופס, אירעה שגיאה. אנא נסה שוב");
			$this->redirect_to(inner_url("userLogin/login/"));
			return;
		}
		$new_pass = $_REQUEST['usr']['password'];
		$new_pass_auth = $_REQUEST['usr']['password_auth'];
		if($new_pass != $new_pass_auth){
			SystemMessages::add_err_message("הסיסמאות חייבות להיות זהות");
			$this->redirect_to(current_url());
			return;
		}
		if(strlen($new_pass) < 6){
			SystemMessages::add_err_message("על הסיסמה להכיל מינימום 6 תוים");
			$this->redirect_to(current_url());
			return;
		}
		UserLogin::reset_password_by_token($token_data, $new_pass);
		SystemMessages::add_success_message("סיסמתך שונתה בהצלחה");
		$this->redirect_to(inner_url("userLogin/login/"));
		return;
	}	

    public function login(){
		if(UserLogin::get_login_state() == "awaiting_sms_code"){
			$this->include_view('user/smsLogin.php');
		}
		else{
			$this->include_view('user/login.php');
		}
    }

    public function logout(){
		session__clear();
		$this->redirect_to(outer_url("userLogin/login/"));
    }	

    public function forgotPassword(){
		$this->include_view('user/forgotPassword.php');
    }
	
	public function register(){
		echo "register is under construction";
		$this->include_view('user/login.php');
    }

	protected function send_login_sms_code($user_phone, $sms_code){
		$this->data['sms_login_code'] = $sms_code;
		//todo: send sms. this small task includes menny sicrets:
		/*  - prepare content,(will be good also for emails)
			- wrap content with another include, 
			- go to global functions for sms sending
		*/
		// currently send via email
		mail('johndo@gmail.com','sms code for login','the code is:'.$sms_code." and tour phone is: ".$user_phone);
	}



  }
?>