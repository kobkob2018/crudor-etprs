<?php
  class biz_rquests_reportsController extends CrudController{
    public $add_models = array("net_banners","net_directories");

    public function list(){
        
        echo "to do: originally it is the 'view_estimate_form_list' function in the 'view_contacts' file";
    }

    public function send_lead_users_list(){
        //get params: biz_request_id, status
        // need to get read of the need for status here...
          // on the biz_request list, will be a link to this function that shows the biz_request and list  the potential users and enables to send to them as leads.
        echo "to do: originally it is the 'send_estimate_form_users_list' function in the 'view_contacts' file";
    }
  }

?>