<?php
class Whatsapp_settingsController extends CrudController{
    public $add_models = array("whatsapp_settings");

    public function check_the_check(){
        
        $api_key = "1234-4321";
        $url = "https://il-biz.co.il/check/check/";



        $data = array(
            'param_1'=> "1",
            "param_2"=> "2"
        );
        


        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_POST, 1 ); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer '.$api_key));
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);
        
        echo $result;
    }

    public function edit(){

        $fields_collection = Whatsapp_settings::setup_kv_field_collection();
        $settings_info = Whatsapp_settings::get();
        $this->data['item_info'] = $settings_info;
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
        
        $form_handler->setup_db_values($settings_info);
        $this->send_action_proceed();
        $this->add_form_builder_data($fields_collection,'updateSend','-1');  
        $this->include_view("whatsapp_settings/settings_form.php");
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
        $settings = Whatsapp_settings::get();
        if(isset($settings[$row['param_name']])){
            SystemMessages::add_err_message("אירעה שגיאה - מאפיין עם אותו מזהה כבר קיים");
            return $this->redirect_to(current_url()); 
        }
        Whatsapp_settings::add_new_kv_param($row);
        SystemMessages::add_success_message("הפרמטר נוסף בהצלחה");
        SystemMessages::add_success_message("שים לב! יש לשים ערך לפרמטר החדש ולשמור");
        return $this->redirect_to(current_url());
    }

    protected function update_item($item_id,$update_values){
        return Whatsapp_settings::update_kv_params($update_values);
    }

    protected function update_success_message(){
        SystemMessages::add_success_message("המאפיינים עודכנו בהצלחה");

    }  

    protected function after_edit_redirect($item_info){
        return $this->redirect_to(current_url());
    } 
}
?>