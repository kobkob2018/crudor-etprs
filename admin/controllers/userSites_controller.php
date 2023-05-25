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

    public function add(){
        return parent::add();
    }  

    public function createSend(){
        return parent::createSend();
    }

    public function delete(){
        echo "under construction";
        return;      
    }

    public function include_add_view(){
        $this->include_view('site/add_site.php');
    }  

    protected function create_success_message(){
        SystemMessages::add_success_message("האתר נוצר בהצלחה");
    }


    protected function delete_success_message(){
        SystemMessages::add_success_message("האתר נמחק");
    }

    protected function delete_item($row_id){
        echo "UNDER CONSTRUCTION";
        return;
       //return Products::delete($row_id);
    }

    public function eject_url(){
        return inner_url('userSites/list/');
    }

    public function url_back_to_item($item_info){
        echo "UNDER CONSTRUCTION";
        return;
        //return inner_url("products/edit/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Sites::setup_field_collection();
    }

    protected function create_item($fixed_values){
        $site_id = Sites::create($fixed_values);
        exit("site id is:".$site_id);
    }

  }
?>