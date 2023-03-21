<?php
  class cron_user_phone_callsModule extends CrudController{

    public $add_models = array("Link_system_calls");


    protected function update_new_calls(){
      $new_calls = Link_system_calls::get_new_calls();
      echo "todo: insert the site_leads_stats...";
    }

  }
?>