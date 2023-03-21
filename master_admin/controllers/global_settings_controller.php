<?php
class Global_settingsController extends CrudController{
    public $add_models = array("global_settings");

    public function edit(){

        $fields_collection = Global_settings::setup_kv_field_collection();
        $settings_info = Global_settings::get();
        $this->data['item_info'] = $settings_info;
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
        
        $form_handler->setup_db_values($settings_info);
        $this->send_action_proceed();
        $this->add_form_builder_data($fields_collection,'updateSend','-1');  
        $this->include_view("global_settings/global_settings_form.php");
    }

    public function add_paramSend(){
        if(!isset($_REQUEST['row'])){
            SystemMessages::add_err_message("אירעה שגיאה");
            return $this->redirect_to(current_url());
        }
        $row = $_REQUEST['row'];
        if($row['param_name'] == '' || $row['label'] == ''){
            SystemMessages::add_err_message("אירעה שגיאה - מאפיין לא תקין");
            return $this->redirect_to(current_url());
        }
        $settings = Global_settings::get();
        if(isset($settings[$row['param_name']])){
            SystemMessages::add_err_message("אירעה שגיאה - מאפיין עם אותו מזהה כבר קיים");
            return $this->redirect_to(current_url()); 
        }
        Global_settings::add_new_kv_param($row);
        SystemMessages::add_success_message("הפרמטר נוסף בהצלחה");
        SystemMessages::add_success_message("שים לב! יש לשים ערך לפרמטר החדש ולשמור");
        return $this->redirect_to(current_url());
    }

    protected function update_item($item_id,$update_values){
        return Global_settings::update_kv_params($update_values);
    }

    protected function update_success_message(){
        SystemMessages::add_success_message("המאפיינים עודכנו בהצלחה");

    }  

    protected function after_edit_redirect($item_info){
        return $this->redirect_to(current_url());
    } 
}
?>