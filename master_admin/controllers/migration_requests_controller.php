<?php
  class Migration_requestsController extends CrudController{
    public $add_models = array("users","migration_requests");

    public function test_db(){
        $test_res = Migration_requests::test_db_connection();
        return "ok now 2";
    }

    public function prepare(){
      $info = array();
      return $this->include_view("migration_requests/prepare.php",$info);
    }

    public function do_migrate_requests(){

        $migrate_result = Migration_requests::do_migrate_requests();
        
        if($migrate_result['status'] == 'done'){
          SystemMessages::add_success_message("כל הלידים יובאו בהצלחה");
        }
        if($migrate_result['status'] == 'found_requests'){
          $request_count = $migrate_result['count'];
          $first = $migrate_result['first'];
          $last = $migrate_result['last'];
          SystemMessages::add_success_message("$request_count לידים יובאו בהצלחה");
          SystemMessages::add_success_message("ליד ראשון: $first");
          SystemMessages::add_success_message("ליד אחרון: $last");
        }
        return $this->redirect_to(inner_url("migration_requests/prepare/"));
    }


    public function delete_older(){   
      Migration_requests::delete_older();
      SystemMessages::add_success_message("הייבוא נמחק בהצלחה.. אוליי.. תשמע כל העסק הזה הוא מה זה קומבינה.. אחר כך צריך למחוק את זה.");
      return $this->redirect_to(inner_url("migration_requests/prepare/"));
    }
    
    

}
?>