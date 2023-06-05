<?php
  class Missing_user_phonesController extends CrudController{
    public $add_models = array("missing_user_phones_reports");

    public function list(){
        //if(session__isset())
       
        $payload = array(
            'order_by'=>'last_call desc'
        );
        $missing_user_phones = Missing_user_phones_reports::get_list(array(),"*",$payload);

        $this->include_view('reports/missing_user_phones_list.php',$missing_user_phones);

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("המספר נמחק מרשימת המספרים החסרים");
    }

    public function delete(){
        
        if(!isset($_GET['row_id'])){
            $this->row_error_message();
            return $this->eject_redirect();
        }
    

        $row_id = $_GET['row_id'];
        $this->delete_item($row_id);
        SystemMessages::add_success_message($this->delete_success_message());
        $this->eject_redirect();
    }
    protected function delete_item($row_id){
      return Missing_user_phones_reports::delete($row_id);
    }

    public function eject_url(){
      return inner_url('missing_user_phones/list/');
    }

  }
?>