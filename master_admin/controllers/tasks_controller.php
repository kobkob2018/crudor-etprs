<?php
	class TasksController extends CrudController{
		public $add_models = array("tasks");
        protected function handle_access($action){
            switch ($action){
                case 'token_login':
                    return true;
                    break;
                default:
                    return $this->call_module(get_config('main_module'),'handle_access_login_only',$action);
                    break;               
            }
        }

        public function list(){
            //if(session__isset())
            $filter_arr = $this->get_base_filter();
            $payload = array(
                'order_by'=>'priority'
            );
            $task_list = Tasks::get_list($filter_arr,"*", $payload);  
            $this->data['task_list'] = $task_list;
            $this->include_view('tasks/list.php');
    
        }
    
        protected function get_base_filter(){
            $filter_arr = array(
                'user_id'=>$this->user['id'],
            );  
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