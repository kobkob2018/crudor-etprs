<?php
	class TasksController extends CrudController{
		public $add_models = array("tasks");
        protected function handle_access($action){
            switch ($action){
                case 'token_login':
                    return true;
                    break;
                default:
                    return parent::handle_access($action);
                    break;               
            }
        }

		public function all(){
            $tasks = array();

            foreach(Tasks::get_list() as $task){
                $user_name_arr = Users::get_by_id($task['user_id'],"full_name");
                $task['user_name'] = $user_name_arr['full_name'];
                $tasks[] = $task;
            }
            $this->data['task_list'] = $tasks;
            $this->include_view('tasks/all.php');
		}

        public function view(){
            if(!isset($_GET['row_id'])){
                return $this->redirect_to(inner_url('tasks/all/'));
            }

            $this->data['task_info'] = Tasks::get_by_id($_GET['row_id']);
            if(!$this->data['task_info']){
                return $this->redirect_to(inner_url('tasks/all/'));
            }

            $fields_collection = Tasks::setup_field_collection();

            $form_handler = $this->init_form_handler();
            $form_handler->update_fields_collection($fields_collection);
            $form_handler->setup_db_values($this->data['task_info']);

            if(isset($_REQUEST['remove_file'])){
                $form_handler->remove_file($_REQUEST['remove_file']);
                $update_values = array('banner'=>'');
                Tasks::update($_GET['row_id'],$update_values);
                SystemMessages::add_success_message("הבאנר הוסר");
                return $this->redirect_to(inner_url("tasks/view/?row_id=".$_GET['row_id']));
            }

            $this->send_action_proceed();

            $form_builder_data = array();
            $form_builder_data['enctype_str'] = 'enctype="multipart/form-data"';
            $form_builder_data['sendAction'] = "updateSend";
            $form_builder_data['row_id'] = $this->data['task_info']['id'];
            $form_builder_data['fields_collection'] = $fields_collection;
            $this->data['form_builder'] = $form_builder_data;           

            $this->include_view('tasks/view.php');
            
		}

        public function updateSend(){
            if(!isset($_REQUEST['row_id'])){
                return;
            }
            $row_id = $_REQUEST['row_id'];
            $form_handler = $this->init_form_handler();
            $validate_result = $form_handler->validate();
            $fixed_values = $validate_result['fixed_values'];
            foreach($fixed_values as $key=>$value){
                $fixed_values[$key] = str_replace('{{row_id}}',$row_id,$value);
            }
            $validate_result['fixed_values'] = $fixed_values;
            if($validate_result['success']){
                Tasks::update($row_id,$fixed_values);
                $files_result = $form_handler->upload_files($validate_result, $row_id);
                SystemMessages::add_success_message("המשימה נשמרה בהצלחה");
                $this->redirect_to(current_url());
            }
            else{
                if(!empty($validate_result['err_messages'])){
                    $this->data['form_err_messages'] = $validate_result['err_messages'];
                }
            }
        }

        public function add(){
            
            $fields_collection = Tasks::setup_field_collection();
            $form_handler = $this->init_form_handler();
            $form_handler->update_fields_collection(Tasks::setup_field_collection());

            $this->send_action_proceed();

            $form_builder_data = array();
            $form_builder_data['enctype_str'] = 'enctype="multipart/form-data"';
            $form_builder_data['sendAction'] = "createSend";
            $form_builder_data['row_id'] = 'new';
            $form_builder_data['fields_collection'] = $fields_collection;
            $this->data['form_builder'] = $form_builder_data;

            $this->send_action_proceed();


            $this->include_view('tasks/add.php');           
		}       

        public function createSend(){
            $work_on_site = Sites::get_user_workon_site();
            $site_id = $work_on_site['id'];
            $form_handler = $this->init_form_handler();
            $validate_result = $form_handler->validate();
            
            if($validate_result['success']){
                $fixed_values = $validate_result['fixed_values'];
                $fixed_values['site_id'] = $site_id;
                $row_id = Tasks::create($fixed_values);

                $fixed_row_values = array();
                foreach($fixed_values as $key=>$value){
                    $fixed_row_value = str_replace('{{row_id}}',$row_id,$value);
                    if($fixed_row_value != $value){
                        $fixed_row_values[$key] = $fixed_row_value;
                    }
                }
                if(!empty($fixed_row_values)){
                    Tasks::update($row_id,$fixed_row_values);
                }
                $validate_result['fixed_values'] = $fixed_values;
                $files_result = $form_handler->upload_files($validate_result, $row_id);
                SystemMessages::add_success_message("המשימה נוספה בהצלחה");
                $this->redirect_to(inner_url("tasks/view/?row_id=$row_id"));
            }
            else{
                if(!empty($validate_result['err_messages'])){
                    $this->data['form_err_messages'] = $validate_result['err_messages'];
                }
            }
        }

        public function delete(){
            if(!isset($_REQUEST['row_id'])){
                SystemMessages::add_err_message("לא נבחרה שורה");
                return $this->redirect_to(inner_url("tasks/all/"));
            }

            $row_id = $_REQUEST['row_id'];
            Tasks::delete($row_id);
            SystemMessages::add_success_message("המשימה נמחקה בהצלחה");
            return $this->redirect_to(inner_url("tasks/all/"));
                
		}

        public function task_email_validate_by($value){
            $return_array =  array(
                'success'=>true
            );
            if($value == 'notgood@gmail.com'){
                $return_array['success'] = false;
                $return_array['message'] = "האימייל שבחרת לא מותר";
            }
            return $return_array;
        }
	}
?>