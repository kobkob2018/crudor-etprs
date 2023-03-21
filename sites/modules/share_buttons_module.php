<?php
	class Share_buttonsModule extends Module{
        public function print(){
            $this->add_data('share_buttons',array('whatsapp'=>
                array('img'=>'https://ilbiz.co.il/ClientSite/version_1/style/image/whatsaap_share.png','href'=>"whatsapp://send?text=".current_url())
            ));
            $this->include_view('share_buttons/print.php');
        }

	}
?>