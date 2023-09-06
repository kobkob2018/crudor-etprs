<?php

  class Deals_closed_paymentsController extends CrudController{
    public $add_models = array('myleads_deals_closed_payments');

    public function report(){
      $user_deals_closed_payments = Myleads_deals_closed_payments::get_user_payments_data($this->user['id']);
	  
	  parent::add();   
	  $this->include_view('deals_closed/report.php',$user_deals_closed_payments);

    }

	protected function get_fields_collection(){
      return Myleads_deals_closed_payments::setup_field_collection();
    }

    protected function create_item($fixed_values){
        $fixed_values['user_id'] = $this->user['id'];
        $fixed_values['pay_type'] = 'deals_closed';
        return Myleads_deals_closed_payments::create($fixed_values);
    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הרישום נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הרישום נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר רישום");
    }   

    protected function delete_item($row_id){
      return Myleads_deals_closed_payments::delete($row_id);
    }	

	public function delete(){
        return parent::delete();      
    }

    public function eject_url(){
      return inner_url('deals_closed_payments/report/');
    }

    public function url_back_to_item($item_info){
      return inner_url('deals_closed_payments/report/');
    }

	protected function get_item_info($row_id){
      return Myleads_deals_closed_payments::get_by_id($row_id);
    }

  }
?>