<?php
	class Whatsapp_chatModule extends Module{
        public function print(){
            if(!is_mobile()){
                return;
            }
            if(!isset($this->controller->data['biz_form_on'])){
                return;
            }
            if(!isset($this->controller->data['page'])){
                return;
            }
            if(isset($this->controller->data['cat_tree'])){
                $show_whatsapp_button = true;
                foreach($this->controller->data['cat_tree'] as $cat){
                    if($cat['show_whatsapp_button'] == '0'){
                        $show_whatsapp_button = false;
                    }
                    else{
                        $show_whatsapp_button = true;
                    }
                }
                if(!$show_whatsapp_button){
                    return;
                }
            }
            
            $button_message = Global_settings::get()['whatsapp_button_message'];
            $page_title = $this->controller->data['page']['title'];
            $button_message = str_replace("{{page_title}}",$page_title,$button_message);
            $action_data = $this->decode_action_data_arr(";");
            $whatsaap_img = styles_url('style/image/whatsapp_chat.png');
            $phone = $action_data['phone'];
            $message = $action_data['message'];
            $message = str_replace("{{whatsapp_page_message}}",$button_message,$message);
            $info = array('image'=>$whatsaap_img,'message'=>$message, 'phone'=>$phone);
            $this->include_view('modules/whatsapp_chat.php',$info);
        }

	}
?>