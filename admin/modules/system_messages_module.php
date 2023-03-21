<?php
	class System_messagesModule extends Module{


		
        public function show(){
            $system_messages = SystemMessages::get_all();
			$this->add_data('success_messages',$system_messages['success']);
			$this->add_data('err_messages',$system_messages['err']);
            $this->include_view('messages/all.php');
        }

	}
?>