<?php
  class Migration_user_cctokensController extends CrudController{
    public $add_models = array("users","migration_user", "migration_user_cctokens");

    public function prepare(){
      //if(session__isset())
      $filter_arr = $this->get_base_filter();


      $migration_user = Migration_user::find($filter_arr);      
      $this->data['migration_user'] = $migration_user;
      if(!$migration_user){
        SystemMessages::add_err_message("יש לבחור משתמש לייבוא");
        return $this->redirect_to(inner_url("migration_user/list/?row_id=".$_REQUEST['user_id']));
      }

      $info = array(
        'migration_exist'=>Migration_user_cctokens::check_if_migration_exist($filter_arr)
      );
      return $this->include_view("migration_user_cctokens/prepare.php",$info);
    }

    protected function get_base_filter(){

        $filter_arr = array(
            'user_id'=>$_REQUEST['user_id']
        );  
        return $filter_arr;     
    }

    public function do_migrate(){
        $filter_arr = $this->get_base_filter();
        $migration_user = Migration_user::find($filter_arr);      
        Migration_user_cctokens::do_migrate($_REQUEST['user_id'],$migration_user);
        SystemMessages::add_success_message("הייבוא בוצע בהצלחה.. מקווים.. זה רק מודול זמני. אחרי שנסיים עם האתרים צריך למחוק את כל הרכיבים של הייבוא מהמערכת, כולל טבלאות ייבוא ועוד");
        return $this->redirect_to(inner_url("migration_user_cctokens/prepare/?user_id=".$_REQUEST['user_id']));
    }


    public function delete_older(){   
        Migration_user_cctokens::delete_older($_REQUEST['user_id']);
        SystemMessages::add_success_message("הייבוא נמחק בהצלחה.. אוליי.. תשמע כל העסק הזה הוא מה זה קומבינה.. אחר כך צריך למחוק את זה.");
        return $this->redirect_to(inner_url("migration_user_cctokens/prepare/?user_id=".$_REQUEST['user_id']));
    }
    
    

}
?>