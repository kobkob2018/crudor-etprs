<?php

  class PagesController extends CrudController{
    public $add_models = array();
    public function error() {
      SystemMessages::add_err_message("Oops! seems like you are in the wrong place");
      $this->include_view('pages/error.php');
    }

    protected function init_setup($action){
      
      return parent::init_setup($action);
    }

    protected function handle_access($action){
      
      switch ($action){
        case 'error':
          return true;
          break;
        default:
          return parent::handle_access(($action));
          break;
        
      }
    }
  }
?>