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

    public function do_migrate_cats(){
        $filter_arr = $this->get_base_filter();
        $migration_site = Migration_site::find($filter_arr);      
        Migration_product::do_migrate_cats($this->data['work_on_site']['id'],$migration_site);
        SystemMessages::add_success_message("ייבוא של תיקיות מוצרים בוצע בהצלחה. יש לייבא את המוצרים עצמם בנפרד");
        return $this->redirect_to(inner_url("migration_product/prepare/"));
    }

    public function do_migrate_products(){
      $filter_arr = $this->get_base_filter();
      $migration_site = Migration_site::find($filter_arr); 

      $migrate_result = Migration_product::do_migrate_products($this->data['work_on_site']['id'],$migration_site);
      
      if($migrate_result['status'] == 'done'){
        SystemMessages::add_success_message("כל המוצרים יובאו בהצלחה");
      }
      if($migrate_result['status'] == 'found_products'){
        $product_count = $migrate_result['count'];
        $first = $migrate_result['first'];
        $last = $migrate_result['last'];
        SystemMessages::add_success_message("$product_count מוצרים יובאו בהצלחה");
        SystemMessages::add_success_message("מוצר ראשון: $first");
        SystemMessages::add_success_message("מוצר אחרון: $last");
      }
      return $this->redirect_to(inner_url("migration_product/prepare/"));
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