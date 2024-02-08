<?php
  class Cron_user_phone_callsModule extends CrudController{

    public $add_models = array("link_system_calls");


    protected function update_new_calls(){
      if(get_config('mode') == 'dev'){
        return;
      }
      $new_calls = Link_system_calls::get_new_calls();
      echo "todo: insert the site_leads_stats...";
    }

  }
?>