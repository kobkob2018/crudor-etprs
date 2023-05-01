<?php
  class UserLogin extends TableModel{


	//user login use not simple functions... still main table will be login_trace
    protected static $main_table = 'login_trace';
   
	private static $instance = NULL;
    private $user_data = false;
	private $login_state = false;

    public function __construct(){
		if(session__isset('login_user')){
			$this->retrive_user_data_from_db();
		}
    }

    private static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new UserLogin();
		}
		return self::$instance;
	}

	public static function get_user() {
		$user = self::getInstance();
		return $user->user_data;
    }

	public static function get_login_state() {
		$user = self::getInstance();
		return $user->login_state;
    }	

	

    private function retrive_user_data_from_db(){
		$db = Db::getInstance();
		$login_trace_data = self::get_login_trace_data();
		if(!$login_trace_data){
			return $this->user_data; //return false (no user yet)
		}

		if($login_trace_data['sms_code'] != ''){
			$this->login_state = 'awaiting_sms_code';
			return $this->user_data; //return false (no user yet)
		}
		else{
			$this->login_state = 'ok';
		}

		$sql = "SELECT * FROM users WHERE id = :user_id";
		$req = $db->prepare($sql);
		$req->execute(array('user_id'=>$login_trace_data['user_id']));
		$user_data = $req->fetch();
		

		$this->user_data = $user_data;
		return $this->user_data;
    }

	protected static function get_login_trace_data(){
		$db = Db::getInstance();
		$user_session = session__get('login_user');
		if($user_session){
			$user_id = $user_session['user_id'];
			$session_id = $user_session['session_id'];
			$user_ip = $_SERVER['REMOTE_ADDR'];
		}
		else{
			return false;
		}

		$sql = "SELECT * FROM login_trace WHERE user_id = :user_id AND session_id = :session_id AND ip = :user_ip";
		$req = $db->prepare($sql);
		$req->execute(array(
			'user_id'=>$user_id,
			'session_id'=>$session_id,
			'user_ip'=>$user_ip,
		));
		$login_trace_data = $req->fetch();
		return $login_trace_data;
	}

	public static function add_login_trace($user_id,$add_sms_code = false){
		$trace_array = array(
			'user_id'=>$user_id,
			'session_id'=>create_session_id(),
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'sms_code'=>'',
		);
		
		if($add_sms_code){
			$trace_array['sms_code'] = rand(10000,99999);
		}


		$db = Db::getInstance();
		$sql = "INSERT INTO login_trace(user_id, session_id, ip, login_time, sms_code) VALUES (:user_id,:session_id, :ip ,NOW(), :sms_code)";
		$req = $db->prepare($sql);
		$req->execute($trace_array);
		
		$user_session = array(
			'user_id'=>$trace_array['user_id'],
			'session_id'=>$trace_array['session_id']
		);
		session__set('login_user',$user_session);
		return $trace_array;
    }

	public static function authenticate($username,$password) {
		$db = Db::getInstance();
		$sql = "SELECT * FROM users WHERE username = :username AND password = :password";
		$req = $db->prepare($sql);
		$req->execute(array('username'=>input_protect($username),'password'=>md5(input_protect($password))));
		$user_data = $req->fetch();
		return $user_data;
    }


	public static function authenticate_mail($email) {
		$db = Db::getInstance();
		$sql = "SELECT * FROM users WHERE email = :email";
		$req = $db->prepare($sql);
		$req->execute(array('email'=>input_protect($email)));
		$user_data = $req->fetch();
		return $user_data;
    }

	public static function authenticate_sms($sms_code) {
		$db = Db::getInstance();
		$login_trace_data = self::get_login_trace_data();
		if(!$login_trace_data){
			return false;
		}

		if($login_trace_data['sms_code'] != $sms_code){
			return false;
		}
		else{
			//delete sms_code and allow user to be logged in
			$sql = "UPDATE login_trace SET sms_code = '' WHERE user_id = :user_id AND session_id = :session_id";
			$req = $db->prepare($sql);
			$req->execute(array(
				'user_id'=>$login_trace_data['user_id'],
				'session_id'=>$login_trace_data['session_id'],
			));
			return true;
		}
    }	

	public static function add_reset_password_token($user_id){
		$insert_array = array(
			'user_id'=>$user_id,
			'token'=>rand(10000,99999),
		);
		
		$db = Db::getInstance();
		$sql = "INSERT INTO user_reset_password(user_id, token) VALUES (:user_id,:token)";
		$req = $db->prepare($sql);
		$req->execute($insert_array);

		$insert_array['row_id'] = $db->lastInsertId();
		return $insert_array;
    }


	public static function authenticate_password_token($row_id,$token){
		$sql_array = array(
			'row_id'=>input_protect($row_id),
			'token'=>input_protect($token),
			'user_ip'=>$_SERVER['REMOTE_ADDR']	
		);
		
		$db = Db::getInstance();
		$sql = "SELECT * FROM user_reset_password WHERE id = :row_id AND token = :token AND (status = '1' OR user_ip = :user_ip)";
		$req = $db->prepare($sql);
		$req->execute($sql_array);
		$token_data = $req->fetch();
		if($token_data && $token_data['status'] == '1'){
			$sql = "UPDATE user_reset_password SET status = '0',user_ip = :user_ip  WHERE id = :row_id AND token = :token";
			$req = $db->prepare($sql);
			$req->execute($sql_array);
		}
		return $token_data;
    }
	
	public static function reset_password_by_token($token_data, $new_pass){
		$user_id = $token_data['user_id'];
		$db = Db::getInstance();
		$sql = "UPDATE users SET password = :password WHERE id = :user_id";
		$req = $db->prepare($sql);
		$req->execute(array(
			'user_id'=>$user_id,
			'password'=>md5(input_protect($new_pass))
		));
		$sql = "DELETE FROM user_reset_password WHERE id = :row_id";
		$req = $db->prepare($sql);
		$req->execute(array(
			'row_id'=>$token_data['row_id'],
		));
	}

	public static function update_details($user_params,$data_user){
		if(!($user_data = self::get_user())){
			return;
		}
		$update_params_arr = array();
		$update_prepere_arr = array('user_id'=>$user_data['id']);
		foreach($user_params as $p_key){
			$insert_val = str_replace("'","''",trim($data_user[$p_key]));
			$new_user[$p_key] = $insert_val;
			$update_params_arr[] = "$p_key = :$p_key";
			$update_prepere_arr[$p_key] = $insert_val;
		}
		$update_params = implode(",",$update_params_arr);
		
		$sql = "update users set $update_params WHERE id = :user_id";
		$db = Db::getInstance();
		$req = $db->prepare($sql);
		$req->execute($update_prepere_arr);
		$user_model = self::getInstance();
		$user_model->retrive_user_data_from_db();
		return $new_user;
    }

  }
?>