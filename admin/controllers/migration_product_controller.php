<?php
  class Migration_productController extends CrudController{
    public $add_models = array("sites","migration_site", "migration_product");

    public function prepare(){
      //if(session__isset())
      $filter_arr = $this->get_base_filter();


      $migration_site = Migration_site::find($filter_arr);      
      $this->data['migration_site'] = $migration_site;
      if(!$migration_site){
        SystemMessages::add_err_message("יש לבחור אתר לייבוא");
        return $this->redirect_to(inner_url("migration_site/list/"));
      }

      $info = array(
        'migration_exist'=>Migration_product::check_if_migration_exist($filter_arr)
      );
      return $this->include_view("migration_product/prepare.php",$info);
    }

    protected function get_base_filter(){

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;     
    }

    public function do_migrate(){
        $filter_arr = $this->get_base_filter();
        $migration_site = Migration_site::find($filter_arr);      
        Migration_product::do_migrate($this->data['work_on_site']['id'],$migration_site);
        SystemMessages::add_success_message("הייבוא בוצע בהצלחה.. מקווים.. זה רק מודול זמני. אחרי שנסיים עם האתרים צריך למחוק את כל הרכיבים של הייבוא מהמערכת, כולל טבלאות ייבוא ועוד");
        return $this->redirect_to(inner_url("migration_product/prepare/"));
    }


    public function delete_older(){   
        Migration_product::delete_older($this->data['work_on_site']['id']);
        SystemMessages::add_success_message("הייבוא נמחק בהצלחה.. אוליי.. תשמע כל העסק הזה הוא מה זה קומבינה.. אחר כך צריך למחוק את זה.");
        return $this->redirect_to(inner_url("migration_product/prepare/"));
    }
    
    

}
?>