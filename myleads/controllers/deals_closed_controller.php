<?php

  class DealsClosedController extends CrudController{
    public $add_models = array('user_cc_token','myleads_pay_by_cc_log');

    public function report(){
      echo "הדף בבנייה";
    }

  }
?>