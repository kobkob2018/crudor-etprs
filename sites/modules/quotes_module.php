<?php
	class QuotesModule extends Module{

        public $add_models = array("SiteQuotes");
        public function print_cat(){        
            $action_data = $this->decode_action_data_arr();
            if(!isset($action_data['cat_id'])){
                return;
            }

            $cat_id = $action_data['cat_id'];
            $quots_arr = SiteQuotes::get_cat_quotes($cat_id);
            if(!$quots_arr){
                return;
            }
            $info = array(
                'quotes_arr'=>$quots_arr
            );
            
            $quote_cat_template = $this->controller->include_ob_view('quotes/print_cat_default.php');
            $this->include_view('quotes/print_cat.php',$info);
        }
	}
?>