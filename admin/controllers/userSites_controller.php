<?php
  class UserSitesController extends CrudController{
    public $add_models = array("sites");

    protected function handle_access($action){
        switch ($action){
            case 'list':
            case 'checkin':
                return $this->call_module('admin','handle_access_login_only');
                break;
            default:
                return parent::handle_access($action);
                break;               
        }
    }

    public function list() {
        $this->call_module('user_sites','raff_list');
    }

    public function checkin(){
        $workon_site = false;
        if(isset($_REQUEST['workon'])){
            $workon_site = Sites::check_user_workon_site($_REQUEST['workon']);
        }
        if(!$workon_site){
            SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
            $this->redirect_to(inner_url('userSites/list/'));
            return;
        }
        else{
            session__set('workon_site',$workon_site);
            $this->redirect_to(inner_url(''));
        }       
    }
  }
?>