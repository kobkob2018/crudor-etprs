<?php

  class Deals_closed_paymentsController extends CrudController{
    public $add_models = array('myleads_deals_closed_payments');

    public function report(){
      $user_deals_closed_payments = Myleads_deals_closed_payments::get_user_payments_data($this->user['id']);
      echo "הדף בבנייה";
    }

  }
?>