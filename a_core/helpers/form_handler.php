<?php
  class Form_handler {
    protected $db_values = array();
    protected $user_values = array();
    protected $messages = array('success'=>array(),'err'=>array());
    protected $fields_collection = array();
    protected $validation_handler;
    protected $files_to_upload;
    protected $fixed_values_for_db = array();
    private $controller_interface;

    public function __construct($controller_interface){
      $this->controller_interface = $controller_interface;
    }

    public function setup_db_values($db_data){
      $this->db_values = $db_data;
    }

    public function validate($input_group = 'row'){
      //generate a validation handler
      require_once('a_core/helpers/validation_handler.php');
      $this->validation_handler = new Validation_handler();

      $validate_result = array(
        'success'=>true,
        'fixed_values'=>array(),
        'err_messages'=>array(),
        'upload_files'=>array(),
      );

      
      //initiate all user sent values
      if(isset($_REQUEST[$input_group])){
        $this->user_values = $_REQUEST[$input_group];
      }
      
      $user_files = $this->get_request_files($input_group);
      foreach($user_files as $key=>$value){
        $this->user_values[$key] = $value['name'];
      }
      
      //loop all fields array
      foreach($this->fields_collection as $field_key=>$field){

        //if the field is not in the form, do not check
        if(!isset($this->user_values[$field_key])){
          continue; 
        }
        
        $user_value = $this->user_values[$field_key];
        $user_fixed_value = $user_value;
        $field_validate_result = array(
          'success'=>true,
        );
        //first assume field is valid
        $field_is_valid = true;

        $validate_payload = array(
          'field'=>$field,
          'key'=>$field_key,
          'input_group'=>$input_group,
          'db_value'=>null,
          'user_value'=>$user_value,
          'last_state'=>array(),
        );

        if(isset($this->db_values[$field_key])){
          $validate_payload['db_value'] = $this->db_values[$field_key];
        }

        if(isset($user_files[$field_key])){
          $validate_payload['user_file'] = $user_files[$field_key];
          $this->user_values[$field_key] = $field['name_file'];
        }
        
        if(isset($field['validation'])){
          //get all validate methods from the field setup
          $validate_methods = explode(",", $field['validation']);
          foreach($validate_methods as $method){
            $method = trim($method);
            if(($method != 'required') && ($user_fixed_value == '' || $user_fixed_value == null)){
              continue;
            }
            //sent each validation to the validation_handler proper method
            $field_validate_result = $this->validation_handler->validate_by($method, $user_fixed_value,$validate_payload);
            $validate_payload['last_state'] = $field_validate_result;
            if(isset($field_validate_result['fixed_value'])){
              $user_fixed_value = $field_validate_result['fixed_value'];
            }
            if(isset($field_validate_result['fixed_value_for_db'])){
              $this->fixed_values_for_db[$field_key] = $field_validate_result['fixed_value_for_db'];
            }
            //the method returns an array with a success boolean and an error message
            if(!$field_validate_result['success']){
              //need to continue checking only if still valid
              $field_is_valid = false;
              break;
            }
          }
        }
        
        //check for custom validation requests. the custom validation methhod will be in the controller
        //check only if stil valid
        if(isset($field['custom_validation']) && $field_is_valid){
          $field_validate_result = $this->validation_handler->validate_by_custom($this->controller_interface, $field['custom_validation'], $user_fixed_value, $validate_payload);
        
          $validate_payload['last_state'] = $field_validate_result;
          if(isset($field_validate_result['fixed_value'])){
            $user_fixed_value = $field_validate_result['fixed_value'];
          }  
          if(isset($field_validate_result['fixed_value_for_db'])){
            $this->fixed_values_for_db[$field_key] = $field_validate_result['fixed_value_for_db'];
          }      
        }

        if($field['type'] == 'file'){
          if($user_fixed_value == ''){
            if(isset($this->db_values[$field_key])){
              $user_fixed_value = $this->db_values[$field_key];
            }
          }
          else{
            if($field_validate_result['success']){
              $file_field = $field;
              $file_field['file_info'] = $user_files[$field_key];
              $validate_result['upload_files'][$field_key] = $file_field;
            }
          }
        }

        $validate_result['fixed_values'][$field_key] = $user_fixed_value;

        if(!$field_validate_result['success']){
          $validate_result['success'] = false;

        }

        if(isset($field_validate_result['message'])){
          $validate_result['err_messages'][] = str_replace( '{{label}}', $field['label'], $field_validate_result['message']);
        }
        
        //todo validation stuff
        // add messages and all
      }
      return $validate_result;
    }

    public function upload_files($validate_result){
      $assets_dir = $this->controller_interface->get_assets_dir();
      foreach($validate_result['upload_files'] as $field_key=>$field){

        $old_file_name = '';
        if(isset($this->db_values[$field_key])){

          $old_file_name = $this->db_values[$field_key];
        }
        $new_file_name = $validate_result['fixed_values'][$field_key];

        $dir_path = $assets_dir['path'];
        /*
          To be alble to upload files to a website directory, make sure you have an avaiable directory with valid permissions
          1 - create a directory : domains/<YOUR DOMAIN>/public_html/assets
          2 - put this command in the command line:  chmod a+rwx domains/<YOUR DOMAIN>/public_html/assets
          
        */
        if( !is_dir($dir_path) )
        {
          SystemMessages::add_err_message("Files can not be uploaded to this domain. Please create directory");
          SystemMessages::add_err_message("Please create directory ".$dir_path);
          SystemMessages::add_err_message("Please ask developer about this (look in helpers/form_handler.php line 153)");
          return;
        }
        $upload_to_arr = explode("/",$field['upload_to']);
        
        foreach($upload_to_arr as $dirname){
          $dir_path.="/$dirname";
          if( !is_dir($dir_path) )
          {
            $oldumask = umask(0) ;
            $mkdir = @mkdir( $dir_path, 0755 ) ;
            if(!$mkdir){
              SystemMessages::add_err_message("Files can not be uploaded to this domain. Please create directory");
              SystemMessages::add_err_message("Please create directory ".$dir_path);
              SystemMessages::add_err_message("Please ask developer about this (look in helpers/form_handler.php line 153)");
              return;
            }
            umask( $oldumask ) ;
          }
        }

        if($old_file_name != ''){
          $old_file_path = $dir_path.'/'.$old_file_name;
          if(file_exists($old_file_path)){  
            unlink($old_file_path);
          }
        }
        $new_file_path = $dir_path.'/'.$new_file_name;
        $tmp_name = $field['file_info']['tmp_name'];

        move_uploaded_file($tmp_name, $new_file_path);
      }
    }

    protected function get_request_files($input_group){
      /*
        the $_FILES array is built very annoyingly, so it's a long function...
      */

      $user_files = array();
      if(isset($_FILES[$input_group])){
        $user_files = array();
        foreach($_FILES[$input_group] as $param_name=>$values_arr){
          foreach($values_arr as $field_name=>$param_value){
            if(!isset($user_files[$field_name])){
              $user_files[$field_name] = array();
            }
            $user_files[$field_name][$param_name] = $param_value;
          }
        }
      }
      return $user_files;
    }

    public function update_fields_collection($fields){
      $this->fields_collection = $fields;
    }


    public function get_select_options($key){
      $selected_index = $this->get_form_input($key);
      $prev_selected_index = $this->fields_collection[$key]['selected_index'];
      if($prev_selected_index != '-1'){
        $this->set_field_selected_str($key,$prev_selected_index,'');
      }
      
      $this->set_field_selected_str($key,$selected_index,'selected');
      return $this->fields_collection[$key]['options'];
    }

    protected function set_field_selected_str($key,$option_index,$str){
      $options_index = $this->fields_collection[$key]['options_index'];
      if(!isset($options_index[$option_index])){
        return;
      }
      $option_key = $options_index[$option_index];
      $this->fields_collection[$key]['options'][$option_key]['selected'] = $str;
    }

    public function get_form_input($key){
      
      $return_value = $this->get_form_input_before_render($key);
      
      if(isset($this->fields_collection[$key])){
        if($this->fields_collection[$key]['type'] == 'text'){
          $return_value = str_replace('"',"&quot;",$return_value);
        }
        if($this->fields_collection[$key]['type'] == 'date'){
          $return_value = $this->get_form_input_before_render($key, array('user'));
         
          //print_help($return_value,'form this');
          if($return_value == '0000-00-00'){
            $return_value = "";
          }
          else{
            $d =  DateTime::createFromFormat('Y-m-d', $return_value);
            if(!$d && isset($this->db_values[$key])){
              $d = DateTime::createFromFormat('Y-m-d', $this->db_values[$key]);
            }
            if(!$d){
              $return_value = "";
            }
            else{
              $return_value = $d->format('d-m-Y');
            }
          }
          //print_help($return_value);
        }
      }
      return $return_value;
    }

    protected function get_form_input_before_render($key, $option_remove = array()){

      if(isset($this->user_values[$key]) && !in_array('user',$option_remove)){
        return $this->user_values[$key];
      }
      if(isset($this->db_values[$key]) && !in_array('db',$option_remove)){
        return $this->db_values[$key];
      }
      if(isset($this->fields_collection[$key]) && !in_array('default',$option_remove)){
        if(isset($this->fields_collection[$key]['default'])){
          return $this->fields_collection[$key]['default'];
        }
      }
      return "";
    }



    public function get_form_file_url($key){

      $assets_dir = $this->controller_interface->get_assets_dir();
      if(!isset($this->fields_collection[$key])){
        return false;
      }
      if(!isset($this->db_values[$key]) || $this->db_values[$key] == ''){
        return false;
      }
      $file_name = $this->db_values[$key];
      $field = $this->fields_collection[$key];
      
      $upload_to = $field['upload_to'];

      $relative_location = "$upload_to/$file_name";
      $full_url = $assets_dir['url'].$relative_location;
      return $full_url;
      
    }

    public function remove_file($field_key){

      if(!isset($this->fields_collection[$field_key])){
        return;
      }

      $field = $this->fields_collection[$field_key];
      $assets_dir = $this->controller_interface->get_assets_dir();
      $dir_path = $assets_dir['path']."/".$field['upload_to'];
      $file_name = $this->db_values[$field_key];
      $file_path = $dir_path."/".$file_name;

      if(file_exists($file_path)){
        unlink($file_path);
      }
      return; 
    }

    public function fix_values_for_update($fixed_values){
      foreach($this->fields_collection as $field_key=>$field){
        if(isset($this->fixed_values_for_db[$field_key]) && isset($fixed_values[$field_key])){
          $fixed_values[$field_key] = $this->fixed_values_for_db[$field_key];
        }
      }
      return $fixed_values;
    }
  }


?>