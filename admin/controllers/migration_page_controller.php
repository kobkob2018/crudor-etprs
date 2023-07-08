<?php
  class Migration_pageController extends CrudController{
    public $add_models = array("sites","migration_site", "migration_page");

    public function list(){
      //if(session__isset())
      $filter_arr = $this->get_base_filter();
      $migration_site = Migration_site::find($filter_arr);      
      $this->data['migration_site'] = $migration_site;
      if(!$migration_site){
        SystemMessages::add_err_message("יש לבחור אתר לייבוא");
        return $this->redirect_to(inner_url("migration_site/list/"));
      }
      else{
          $filter = array(
            'row_limit' => '20',
            'page'=>'1'
          );
          if(isset($_REQUEST['page'])){
            $filter['page'] = $_REQUEST['page'];
          }
          $migrate_page_info = Migration_page::get_old_site_page_list($migration_site,$filter);
          $this->data['migrate_page_list'] = $migrate_page_info['content_pages'];
          
          $row_count = $migrate_page_info['row_count'];
          $next_page = true;
          $row_limit = intval($filter['row_limit']);
          $page_i = 1;
          $page_options = array();
          $page = intval($filter['page']);
          while($next_page){
              $page_option = array(
                  'index'=>$page_i,
                  'selected_str'=>'',
              );

              if($page_i == $page){
                  $page_option['selected_str'] = ' selected ';
              }
              $page_options[] = $page_option;
              $limit_count = $page_i*$row_limit;
              if($limit_count < $row_count){
                  $page_i++;
              }
              else{
                  $next_page = false;
              }
          }
          $this->data['page_options'] = $page_options;
      }
      return $this->include_view("migration_page/list.php");
    }

    protected function get_base_filter(){

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;     
    }

	public function import_page(){
		$this->set_layout("blank");
		$filter_arr = $this->get_base_filter();
		$migration_site = Migration_site::find($filter_arr);
		$page_id = $_REQUEST['page_id'];
		
		$import_info = Migration_page::import_page($page_id,$migration_site);
		$return_array = array(
			"old_page_id"=>$page_id,
			"page_id"=>$import_info['page_id'],
			"version"=>$import_info['version'],
			"cat_str"=>$import_info['cat_str']
		);
		print(json_encode($return_array));
		return;
	}
	
	public function delete_migration(){
		$this->set_layout("blank");
		$filter_arr = $this->get_base_filter();
		$migration_site = Migration_site::find($filter_arr);
		$page_id = $_REQUEST['page_id'];
		$delete_info = Migration_page::delete_migrated_page($page_id,$migration_site);
		$return_array = array(
			"old_page_id"=>$delete_info['old_page_id'],
			"page_id"=>$page_id
		);
		print(json_encode($return_array));
		return;
	}

  public function get_page_blocks(){
    $this->set_layout("blank");
    $page_id = $_REQUEST['page_id'];
    $page_blocks = Migration_page::get_page_blocks($page_id);
    $this->data['page_blocks'] = $page_blocks;
    return $this->include_view("migration_page/page_blocks.php");
  }

  public function migrate_image(){
    $this->set_layout("blank");
    $filter_arr = $this->get_base_filter();
    $migration_site = Migration_site::find($filter_arr);
    $img_url = $_REQUEST['img_url'];
    $check_url = "http://";
    if($migration_site['old_has_ssl']){
      $check_url = "https://";
    }
    $check_url.=$migration_site['old_domain'];
    $return_array = array(
      'new_img_src'=>$img_url
    );
    $img_filter_arr = array('old_src'=>$img_url);
    $image_row = Migration_page::simple_find_by_table_name($img_filter_arr,'migration_image');
    if($image_row){
      $return_array['new_img_src'] = $image_row['new_src'];
    }
    else{
      $file_name = basename($img_url).PHP_EOL;
      $file_name = str_replace(" ","-",$file_name);
      $dir_path = $this->create_uploads_path($migration_site);
      $file_path = $dir_path.$file_name;
      $limit_attampts = 1;
      while($limit_attampts < 5){
        if(file_exists($file_path)){
          $limit_attampts++;
          $new_filename = "f".$limit_attampts.$file_name;
          $file_path = $dir_path.$new_filename;
        }
        else{
          $limit_attampts = 10;
        }
      }
      file_put_contents($file_path, file_get_contents($img_url));
      $return_array['new_img_src'] = $file_path;
      Migration_page::simple_create_by_table_name(array(
        'new_src'=>$file_path,
        'old_src'=>$img_url,
        'site_id'=>$migration_site['site_id']
      ),'migration_image');
    }
    
    print(json_encode($return_array));

  }  
  protected function create_uploads_path($migration_site){
    $dir_path = 'assets_s/'.$migration_site['site_id']."/";
    if( !is_dir($dir_path)){
      $oldumask = umask(0) ;
      $mkdir = @mkdir( $dir_path, 0755 ) ;
      umask( $oldumask ) ;
    }
    $dir_path = $dir_path."media/";
    if( !is_dir($dir_path)){
      $oldumask = umask(0) ;
      $mkdir = @mkdir( $dir_path, 0755 ) ;
      umask( $oldumask ) ;
    }
    $dir_path = $dir_path."uploads/";
    if( !is_dir($dir_path)){
      $oldumask = umask(0) ;
      $mkdir = @mkdir( $dir_path, 0755 ) ;
      umask( $oldumask ) ;
    }
    return $dir_path;
  }
}
?>