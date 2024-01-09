<?php
	class cron_auto_login_tokenModule extends Module{
        public $add_models = array("auto_login_token");

        //every morning clean auto login token that are older then 4 days
        public function expiry_cleanups(){
            Auto_login_token::clean_old_tokens('4');   
        }
	}
?>