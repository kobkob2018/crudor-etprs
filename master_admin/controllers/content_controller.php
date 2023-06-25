<?php
class ContentController extends CrudController{
  public $add_models = array("masterSites_content");


  protected function find_in_sites(){
      $search_term = "";
      $info = array(
        'search_term'=>$search_term,
        'search_results'=>false
      );
      if(isset($_REQUEST['search_term'])){
        if($_REQUEST['search_term'] == ""){
          SystemMessages::add_err_message("יש להוסיף ערך חיפוש");
          return $this->redirect_to(current_url());
        }
        $search_term = $_REQUEST['search_term'];
        $search_results = MasterSites_content::find_in_sites($_REQUEST['search_term']);
        $info = array(
          'search_term'=>$search_term,
          'content_pages_list'=>$search_results['content_pages_list'],
          'landing_pages_list'=>$search_results['landing_pages_list'],
          'product_list'=>$search_results['product_list'],
          'search_results'=>true
        );
      }

      return $this->include_view('content/find_in_sites.php',$info);
  }




}
?>