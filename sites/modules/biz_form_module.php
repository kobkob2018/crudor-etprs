<?php
// to debug here, put in js console: help_debug_forms();

	class Biz_formModule extends Module{
        
        public $add_models = array("biz_categories","siteBiz_forms","cities");
        public function fetch_form(){
            $biz_form_data = siteBiz_forms::get_current_biz_form();
            if(!$biz_form_data){
                return;
            }
            
            $this->add_data('biz_form',$biz_form_data);
            $city_select = array(
                'options'=>Cities::get_flat_select_city_options()
            );
            $this->add_data('city_select',$city_select);



            $input_remove = array();
            if($biz_form_data['input_remove'] != ''){
                $input_remove_arr = explode(',',$biz_form_data['input_remove']);
                foreach($input_remove_arr as $input_name){
                    $input_name = trim($input_name);
                    $input_remove[$input_name] = '1';
                }
            }
            $info = array(
                'biz_form'=>$biz_form_data,
                'input_remove'=>$input_remove
            );

            if(isset($_GET['custom_phone'])){
                $info['custom_phone'] = $_GET['custom_phone'];
            }

            if(isset($_GET['custom_cat'])){
                $custom_cat = $_GET['custom_cat'];
                $cat_tree = Biz_categories::get_item_parents_tree($custom_cat,'parent, label');
                $cat_title_arr = array();
                foreach($cat_tree as $cat){
                    $cat_title_arr[] = $cat['label'];
                }
                $custom_cat_title = implode(", ",$cat_title_arr);
                $info['custom_cat_title'] = $custom_cat_title;
                $info['biz_form']['cat_id'] = $custom_cat;
            }

            $this->include_view('biz_form/init_form.php',$info);
        }

	}
?>