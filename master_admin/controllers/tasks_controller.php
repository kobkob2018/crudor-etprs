<?php
	class TasksController extends CrudController{
		public $add_models = array("tasks");
        protected function handle_access($action){
            return $this->call_module(get_config('main_module'),'handle_access_login_only',$action);
        }

        public function list(){
            //if(session__isset())
            $filter_arr = $this->get_base_filter();
            $payload = array(
                'order_by'=>'priority'
            );
            $task_list = Tasks::get_list($filter_arr,"*", $payload);  

            $status_list = Tasks::$fields_collection['status']['options'];
            $status_list = Helper::eazy_index_arr_by('value',$status_list,'title');

            $user_options = Tasks::get_select_user_options();
            

                
            foreach($user_options as $key=>$option){
                $selected = "";
                if(isset($filter_arr['user_id'])){
                    if($filter_arr['user_id'] == $option['value']){
                        $selected = "selected";
                    }
                }
                $user_options[$key]['selected_str'] = $selected;
                    
            }
            
            foreach($task_list as $key=>$task){
                $user_name_arr = Users::get_by_id($task['user_id'],"full_name");
                if($user_name_arr){
                    $task['user_name'] = $user_name_arr['full_name'];
                }
                else{
                    $task['user_name'] = "USER DELETED!!!";
                }
                $task_list[$key] = $task;
            }
            $this->data['task_list'] = $task_list;
            $info = array(
                'user_options'=>$user_options,
                'status_list'=>$status_list
            );
            $this->include_view('tasks/list.php',$info);
    
        }
    
        protected function get_base_filter(){
            $user_id = $this->user['id'];
            if($this->view->user_is('master_admin')){
                if(session__isset('tasks_user_id')){
                    $user_id = session__get('tasks_user_id');
                }
                if(isset($_GET['user_id'])){
                    $user_id = $_GET['user_id'];
                    session__set('tasks_user_id',$_GET['user_id']);
                }
            }
           
            $filter_arr = array(
                'user_id'=>$user_id,
            );  

            if($user_id == 'all'){
                unset($filter_arr['user_id']);
            }

            return $filter_arr;     
        }
    
        public function edit(){
            return parent::edit();
        }
    
        public function updateSend(){
            return parent::updateSend();
        }
    
        public function add(){
            return parent::add();
        }       
    
        public function createSend(){
            return parent::createSend();
        }
    
        public function delete(){
            return parent::delete();      
        }
    
        public function set_priority(){
            return parent::set_priority();
        }
    
        public function rearange_priority($filter_arr){
            return Tasks::rearange_priority($this->get_base_filter());
        }
    
    
    
        public function include_edit_view(){
            $this->include_view('tasks/edit.php');
        }
    
        public function include_add_view(){
            $this->include_view('tasks/add.php');
        }   
    
        protected function update_success_message(){
            SystemMessages::add_success_message("המשימה עודכנה בהצלחה");
    
        }
    
        protected function create_success_message(){
            SystemMessages::add_success_message("המשימה נוצרה בהצלחה");
    
        }
    
        protected function delete_success_message(){
            SystemMessages::add_success_message("המשימה נמחקה");
        }
    
        protected function row_error_message(){
          SystemMessages::add_err_message("לא נבחרה משימה");
        }   
    
        protected function delete_item($row_id){
          return Tasks::delete($row_id);
        }
    
        protected function get_item_info($row_id){
          return Tasks::get_by_id($row_id);
        }
    
        public function eject_url(){
          return inner_url("tasks/list/");
        }
    
        public function url_back_to_item($item_info){
          return inner_url("tasks/list/");
        }

        public function delete_url($item_info){
            return inner_url("tasks/delete/?row_id=".$item_info['id']);
        }        

        protected function get_fields_collection(){
          return Tasks::setup_field_collection();
        }
    
        protected function update_item($item_id,$update_values){
          return Tasks::update($item_id,$update_values);
        }
    
        protected function get_priority_space($filter_arr, $item_to_id){
            return Tasks::get_priority_space($filter_arr, $item_to_id);
          }
    
        protected function create_item($fixed_values){
            return Tasks::create($fixed_values);
        }


	}
?>