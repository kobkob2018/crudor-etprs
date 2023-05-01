<?php
  class Controller {
	public $use_models = array("userLogin","users","user_rolls","systemMessages","global_settings");
	public $add_models = array();
	public $data = array();
	public $user = false;
	public $action_output = "";
	public $action_params = array('layout_file'=>'layouts/layout.php','body_layout_file'=>'body/main.php'); 
	public $action_result;
	public $body_class = "";
	public $form_handlers = array();
	public $registered_scripts = array("head"=>array(),"foot"=>array(),"all"=>array());
	protected $view;
	protected $send_action_state = false;
	
    public function __construct() {
		
		
		foreach($this->use_models as $add_model){
			$this->add_model($add_model);
		}
		foreach($this->add_models as $add_model){
			$this->add_model($add_model);
		}
		$this->view = new View($this);
		$this->user = Users::get_loged_in_user();
		global $controller,$action;
		$this->body_class = $controller."_".$action;
    }

	protected function handle_access($action){
		return $this->call_module(get_config('main_module'),'handle_access_default',$action);
	}

	protected function init_setup($action){
		//this is a function to override
		return true;
	}

	public function print_layout($action){

		if(!$this->handle_access($action)){
			return;
		}
		
		//ask main module of the system to colect vital global data 
		$this->call_module(get_config('main_module'),'init_layout',$action);
		
		//call the override setup function of the specific controller
		$init_setup_result = $this->init_setup($action);
		if(!$init_setup_result){
			return;
		}
		
		ob_start();
		$this->action_result = $this->$action();
		$this->action_output = ob_get_clean();

		//send_action_proceed can be also caled from the view function
		$this->send_action_proceed();

		$this->include_view($this->action_params['layout_file']);
		
		return;

	}

	protected function send_action_proceed(){
		if($this->send_action_state){
			return;
		}
		$this->send_action_state = true;

		if(isset($_REQUEST['sendAction'])){
			$method_name = $_REQUEST['sendAction'];
			if(method_exists($this,$method_name)){
				$this->$method_name();
			}
		}
	}

	public function add_model($model_name){
		if(in_array($model_name,get_config('a_core_models')) && !in_array($model_name,get_config('override_models'))){
			require_once('a_core/models/'.$model_name.'_model.php');
		}
		else{
			system_require_once('models/'.$model_name.'_model.php');
		}
	}

	protected function set_layout($layout_name){
		$this->action_params['layout_file'] = 'layouts/'.$layout_name.'.php';
	}

	protected function set_body($body_name){
		$this->action_params['body_layout_file'] = 'body/'.$body_name.'.php';
	}

	public function print_action_output(){
		print($this->action_output);
	}

	public function print_body(){
		ob_start();
		$this->include_view($this->action_params['body_layout_file']);
		$body_output = ob_get_clean();
		$body_output = $this->call_module(get_config('main_module'),'proccess_body_modules',$body_output);
		print($body_output);
		/*
			on blank layout this is not called
		*/ 
	}

	//this is to add view from modules
	public function include_view($view_path, $info_payload = array()){
		$view = $this->view;
		$info = $info_payload;
		$views_dir_path = "views/";
		include(system_path($views_dir_path.$view_path));
	}

	public function include_ob_view($view_path, $info_payload = array()){
        ob_start();
		$this->include_view($view_path, $info_payload);
		return ob_get_clean();
	}

	public function redirect_to($url){
		$this->set_layout('blank');
		header('Location: '.$url);
	}
	
	public function call_module($module_name,$action_name, $action_data = null){
		
		if(system_file_exists('modules/' . $module_name . '_module.php')){
			system_require_once('modules/' . $module_name . '_module.php');
			$module_class = ucfirst($module_name)."Module";
			if(method_exists(ucfirst($module_class),$action_name)){		
				$module = new $module_class($this, $action_data);
				return $module->$action_name();
			}
			else{
				//this param not exist so it will invoke notice here
				
				echo $module_method_not_found_worning;
				//do nothing
			}			
		}
		else{
			
			//this param not exist so it will invoke notice here
			echo $modulenotfound_worning;
			//do nothing
		}
	}

	//this is to add data from modules (this controller is the module interface)
	public function add_data($dataName,$dataVal){
		$this->data[$dataName] = $dataVal;
	}

	//this is to add data from modules (this controller is the module interface)
	public function add_module_data($dataName,$dataVal){
		//currently we add module data as norrmal data. considering care for naming convension
		$this->add_data($dataName,$dataVal);
	}

	public function send_email($email_to, $email_title,$email_content){ 
		return Helper::send_email($email_to, $email_title,$email_content);
	}

	public function init_form_handler($form_id = 'main'){		
		if(isset($this->form_handlers[$form_id])){
			return $this->form_handlers[$form_id];
		}
		require_once('a_core/helpers/form_handler.php');
		$this->form_handlers[$form_id] = new Form_handler($this);
		return $this->form_handlers[$form_id];
	}
	
	protected function get_form_input($param,$form_id='main'){
		return $this->init_form_handler($form_id)->get_form_input($param);
	}

	protected function get_form_file_url($param,$form_id='main'){
		return $this->init_form_handler($form_id)->get_form_file_url($param);
	}

	protected function get_select_options($param,$form_id='main'){
		return $this->init_form_handler($form_id)->get_select_options($param);
	}

	protected function get_file_input($param,$form_id='main'){
		return $this->init_form_handler($form_id)->get_file_input($param);
	}	
	
	public function get_assets_dir($relative_site = 'self'){
		$info = array(
			'relative_site'=>$relative_site
		);
		return $this->call_module(get_config('main_module'),'get_assets_dir',$info);
	}

	public function register_script($type,$label, $ref,$place = 'head',$order = array()){
		if(!isset($this->registered_scripts["all"][$label])){
			$this->registered_scripts["all"][$label] = true;
			$new_script = array('type'=>$type,'ref'=>$ref);
			$reg_arr = $this->registered_scripts[$place];

			$index = false;
			$order_place = 0;
			$keys = array_keys( $reg_arr );
			if(isset($order['before'])){
				$index = array_search( $order['before'], $keys );
			}
			if(isset($order['after'])){
				$index = array_search( $order['after'], $keys );
				$order_place = 1;
			}
			$pos = false === $index ? count( $reg_arr ) : $index + $order_place;
			$reg_arr = array_slice( $reg_arr, 0, $pos ) + array($label=>$new_script) + array_slice( $reg_arr, $pos );
			$this->registered_scripts[$place] = $reg_arr;

		}

	}

	public function print_json_page($print_result){		
		$print_result = $this->add_essential_ajax_info($print_result);
		$this->set_layout('blank');
		echo json_encode($print_result);
	}

	protected function add_essential_ajax_info($print_result){
		//to be overriden
		return $this->call_module(get_config('main_module'),'add_global_essential_ajax_info',$print_result);
	}
	
  }
?>