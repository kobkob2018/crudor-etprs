<?php
  class SiteController extends CrudController{
    public $add_models = array("adminSites","adminPages");

    protected function handle_access($action){
      if($action == "add"){
        if(Helper::user_is("master_admin",$this->user)){
          return true;
        }
      }
      return $this->call_module('admin','handle_access_user_is','master_admin');
    }

    public function edit(){
      $this->data['row_id'] = $this->data['work_on_site']['id'];
      return parent::edit();
    }

    public function updateSend(){
      return parent::updateSend();
    }

    public function include_edit_view(){
      if(isset($this->data['item_info'])){
        $this->data['site_info'] = $this->data['item_info'];
      }
      $this->include_view('site/edit_site.php');
    }

    protected function update_success_message(){
      SystemMessages::add_success_message("האתר עודכן בהצלחה");

    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר אתר");
    }   


    protected function get_item_info($row_id){
      return Sites::get_user_workon_site();
    }

    public function eject_url(){
      return inner_url('tasks/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site/edit/");
    }

    public function delete_url($item_info){
      return inner_url("site/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      $fields_collection = AdminSites::$fields_collection;
      if($_REQUEST['action'] == 'add'){
        foreach($fields_collection as $key=>$field){
          if($field['type'] == 'file'){
            unset($fields_collection[$key]);
          }
        }
        unset($fields_collection['home_page']);
      }
      return AdminSites::setup_field_collection($fields_collection);
    }

    protected function update_item($item_id,$update_values){
      return Sites::update($item_id,$update_values);
    }


    public function add(){
      return parent::add();
    }  

    public function createSend(){
        return parent::createSend();
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

    public function delete(){
      $this->data['site_info'] = $this->data['work_on_site'];
      if(isset($_REQUEST['confirm_delete_final']) && $_REQUEST['confirm_delete_final'] == $this->data['work_on_site']['domain']){
        return parent::delete();
      }
      $this->include_view('site/delete_site_confirm.php');
    }

    protected function delete_item($row_id){
      $site_directory = "assets_s/$row_id/";
      self::rmdir_recursive($site_directory, true);
      AdminSites::delete($row_id);
      session__unset('workon_site');
      return;
    }

    protected function create_item($fixed_values){

        $site_id = Sites::create($fixed_values);
        session__set('workon_site',$site_id);
        $this->add_model("siteUsers");
        SiteUsers::create(array(
          'user_id'=>$this->user['id'],
          'site_id'=>$site_id,
          'roll'=>'master_admin'
        ));
        
        $duplicate_site = Sites::get_by_domain(Global_settings::get()['duplicate_domain']);
        $duplicate_site_filter = array("site_id"=>$duplicate_site['id']);
        $this->add_model("adminSite_colors");
        $duplicate_site_colors = AdminSite_colors::get_list($duplicate_site_filter);
        if(!$duplicate_site_colors){
          SystemMessages::add_err_message("האתר נוצר בהצלחה אך לא ניתן לשכפל מאתר קיים. אנא הגדר אתר לשכפול בהגדרות כלליות בניהול ראשי.");
          return;
        }
        foreach($duplicate_site_colors as $site_color){
          $site_color['site_id'] = $site_id;
          unset($site_color['id']);
          AdminSite_colors::create($site_color);
        }

        $this->add_model("site_styling");
        $duplicate_site_styling = Site_styling::find($duplicate_site_filter);
        if($duplicate_site_styling){
          unset($duplicate_site_styling['id']);
          $duplicate_site_styling['site_id'] = $site_id;
          Site_styling::create($duplicate_site_styling);
        }        

        $this->add_model("adminPages");
        
        $duplicate_page_id = $duplicate_site['home_page'];
        $duplicate_page = AdminPages::get_by_id($duplicate_page_id);


        if(!$duplicate_page){
          return;
        }
        $duplicate_page_filter = array(
          'site_id'=>$duplicate_site['id'],
          'page_id'=>$duplicate_page_id
        );

        $duplicate_page['site_id'] = $site_id;
        unset($duplicate_page['id']);
        $new_page_id = AdminPages::create($duplicate_page);

        Sites::update($site_id,array('home_page'=>$new_page_id));
        $this->add_model("adminBlocks");

        $duplicte_blocks = AdminBlocks::get_list($duplicate_page_filter);
        if($duplicte_blocks){
          foreach($duplicte_blocks as $duplicate_block){
            $duplicate_block['site_id'] = $site_id;
            $duplicate_block['page_id'] = $new_page_id;
            unset($duplicate_block['id']);
            AdminBlocks::create($duplicate_block);
          }
        }

        $this->add_model("page_style");

        $page_style_duplicate = Page_style::find($duplicate_page_filter);

        if($page_style_duplicate){
          $page_style_duplicate['site_id'] = $site_id;
          $page_style_duplicate['page_id'] = $new_page_id;
          unset($page_style_duplicate['id']);
          Page_style::create($page_style_duplicate);
        }

        $this->add_model("biz_forms");

        $biz_form_duplicate = Biz_forms::find($duplicate_page_filter);
        if($biz_form_duplicate){
          $biz_form_duplicate['site_id'] = $site_id;
          $biz_form_duplicate['page_id'] = $new_page_id;
          unset($biz_form_duplicate['id']);
          Biz_forms::create($biz_form_duplicate);
        }
        
        $this->add_model("adminMenuItems");
        $duplicate_menus_filter = $duplicate_site_filter;
        $duplicate_menus_filter['parent'] = '0';
        $duplicte_menus = AdminMenuItems::get_list($duplicate_menus_filter);
        if($duplicte_menus){
          foreach($duplicte_menus as $menu_item){
            $menu_item['site_id'] = $site_id;
            unset($menu_item['id']);
            AdminMenuItems::create($menu_item);
          }
        }    
        
        SystemMessages::add_success_message("האתר נוצר בהצלחה ושוכפל בהצלחה");
        SystemMessages::add_err_message("שים לב: על מנת להפעיל את ערכת הצבעים, יש לשמור מחדש את ערכת הצבעים(לחץ שליחה כאן, בתחתית הטופס)");
        SystemMessages::add_err_message("לאחר מכן יש להוסיף לוגו ופביקון בניהול האתר");
        
        return $site_id;
    }

    protected function after_add_redirect($new_row_id){
      return $this->redirect_to(inner_url('site_colors/edit/?row_id='.$new_row_id));
    }


    public static function rmdir_recursive($directory, $delete_parent = null){
      $files = glob($directory . '/{,.}[!.,!..]*',GLOB_MARK|GLOB_BRACE);
      foreach ($files as $file) {
        if (is_dir($file)) {
          self::rmdir_recursive($file, 1);
        } else {
          unlink($file);
        }
      }
      if ($delete_parent) {
        rmdir($directory);
      }
    }
  }
?>