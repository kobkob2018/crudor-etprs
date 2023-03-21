<?php
  class SearchController extends CrudController{
    //public $add_models = array("sites");

    protected function init_setup($action){
      $this->data['page_meta_title'] = $this->data['site']['meta_title'];
      return parent::init_setup($action);
    }

    protected function results(){
        echo "כאן יהיו תוצאות חיפוש";
    }

  }
?>