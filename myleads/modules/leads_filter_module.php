<?php
	class Leads_filterModule extends Module{
        
        public $add_models = array("leads_filter");
        public function add_filter_form(){
            $leads_filter = $this->add_filter_data();

            $fields_collection = $this->get_fields_collection();
            foreach($fields_collection as $field_key=>$build_field){
                if(!isset($build_field['front_attributes'])){
                    $fields_collection[$field_key]['front_attributes'] = "";
                }
            }
            $form_builder_data = array(
                'fields_collection'=>$fields_collection
            );
            $this->add_data('leads_filter_form_builder', $form_builder_data);

            $form_handler = $this->controller->init_form_handler("leads_filter");
            $form_handler->update_fields_collection($fields_collection);
            $form_handler->setup_db_values($leads_filter);

            $this->include_view('leads/filter_form.php');
        }

        public function add_filter_data(){
            $leads_filter = array();
            if(isset($this->controller->data['leads_filter'])){
                $leads_filter =$this->controller->data['leads_filter'];
                return $leads_filter;
            }
            elseif(session__isset('leads_filter')){
                $leads_filter = session__get('leads_filter');
            }
            $this->add_data('leads_filter',$leads_filter);
            return $leads_filter;
        }

        protected function get_fields_collection(){
            return Leads_filter::setup_field_collection();
        }
	}
?>