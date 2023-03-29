<?php
	class Share_buttonsModule extends Module{
        public function print(){
            $whatsaap_share = styles_url('style/image/whatsaap_share.png');
            $this->add_data('share_buttons',array('whatsapp'=>
                array('img'=>$whatsaap_share,'href'=>"whatsapp://send?text=".current_url())
            ));
            $this->include_view('share_buttons/print.php');
        }

	}
?>