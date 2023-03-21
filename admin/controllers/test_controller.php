<?php
  class TestController extends CrudController{

    protected function handle_access($action){
      return true;
    }
    public $add_models = array("test");
    public function pixelize_me(){
      $this->set_layout("blank");
      header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_REFERER']);
      $label = $_REQUEST['label'];
      $add_arr = array('label'=>$label);
      Test::create($add_arr);
    }

    public function redirected_here(){
      
      echo "<h1>HA HA redirected</h1>";
      print_r_help($_REQUEST,"here are some params");
    }
  }
?>