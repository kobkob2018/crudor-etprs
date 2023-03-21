<?php
  class Site_colorsController extends CrudController{
    public $add_models = array("adminSites","AdminSite_colors");

    protected function handle_access($action){
        
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
      $this->include_view('site_colors/edit_colors.php');
    }

    protected function update_success_message(){
      SystemMessages::add_success_message("צבעי האתר עודכנו בהצלחה");

    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר אתר");
    }   


    protected function get_item_info($row_id){
      return AdminSite_colors::get_site_colors($row_id);
    }

    public function eject_url(){
      return inner_url('tasks/all/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site_colors/edit/");
    }


    protected function get_fields_collection(){
      return AdminSite_colors::setup_field_collection();
    }

    protected function update_item($site_id,$update_values){


        $assets_dir = $this->get_assets_dir('self');
        $asset_mapping = AdminSite_colors::$asset_mapping;
        $css_file_location = $asset_mapping['colors_css_file'];
        $css_file_name = "colors.css";
        $dir_path = $assets_dir['path'].$css_file_location;
        if(!is_dir($dir_path)){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( $dir_path, 0755 ) ;
            umask( $oldumask ) ;
        }
        $file_path = $dir_path."/".$css_file_name;
        
        if(file_exists($file_path)){
            unlink($file_path);
        }

        $info = array('colors'=>$update_values);

        $css_text = $this->include_ob_view("site_colors/css_template.php",$info);

        
        $css_file = fopen($file_path, "w");
        fwrite($css_file, $css_text);
        fclose($css_file);
        //print_help($file_path);
        SystemMessages::add_success_message($file_path);
        return AdminSite_colors::update_site_colors($site_id,$update_values);
    }

  }
?>