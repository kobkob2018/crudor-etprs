<?php
  class Migration_page extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_page';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function get_old_site_page_list($migration_site, $filter){


        $ilbiz_db = self::getIlbizDb();
        $sql = "select COUNT(id) as row_count FROM content_pages page 
		
		WHERE page.unk = :unk 
        AND page.deleted = '0' 
		AND page.redierct_301 = '' 
		AND page.type NOT IN('text','net','gb','contact')  
        ";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $result = $req->fetch();

        $row_count = $result['row_count'];

        $page = intval($filter['page']);

        $row_limit = intval($filter['row_limit']);
        $limit_count = $page*$row_limit;
        $limit_str = " LIMIT $limit_count, $row_limit ";

        $ilbiz_db = self::getIlbizDb();
        $sql = "select page.*, cat_spec, subCat, primeryCat from content_pages page 
		LEFT JOIN estimate_miniSite_defualt_block form ON form.type= page.id
		WHERE page.unk = :unk 
        AND page.deleted = '0' 
		AND page.redierct_301 = '' 
		AND page.type NOT IN('text','net','gb','contact')  
         $limit_str 
        ";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $result = $req->fetchAll();
        $content_pages = array();
        $formated_params = array(
            'name', 'content', 'keywords', 'description'
        );
        $old_cat_str_arr = array();
        $new_cat_str_arr = array();
        if($result){
            foreach($result as $content_page){
                foreach($formated_params as $param){
					if($content_page[$param] != ""){
						$content_page[$param] = utgt($content_page[$param]);
					}
				}
				$cat_id = '0';
				if($content_page['primeryCat'] != "" && $content_page['primeryCat'] != '0'){
					$cat_id = $content_page['primeryCat'];
				}
				if($content_page['subCat'] != "" && $content_page['subCat'] != '0'){
					$cat_id = $content_page['subCat'];
				}
				if($content_page['cat_spec'] != "" && $content_page['cat_spec'] != '0'){
					$cat_id = $content_page['cat_spec'];
				}
				
				if(!isset($old_cat_str_arr[$cat_id])){
					$old_cat_str_arr[$cat_id] = self::get_ilbiz_cat_str($cat_id);
				}

				if(!isset($new_cat_str_arr[$cat_id])){
					$new_cat_str_arr[$cat_id] = self::get_ilbiz_migrate_cat_str($cat_id);
				}

				
				$content_page['cat_id'] = $cat_id;
                $old_cat_str = $old_cat_str_arr[$cat_id];
				$content_page['old_cat_str'] = $old_cat_str;
                $new_cat_str = $new_cat_str_arr[$cat_id];
				$content_page['new_cat_str'] = $new_cat_str;
                $content_pages[] = $content_page;
            }
        }
		
        $migrated_pages = self::get_list(array('site_id'=>$migration_site['site_id']));

        $migrated_pages_indexed = Helper::eazy_index_arr_by('old_page_id',$migrated_pages);

        foreach($content_pages as $key=>$content_page){
            $migrated_page = array(
                'migrated'=>false,
                'id'=>'',
                'page_id'=>'',
                'version'=>'',
                'cat_str'=>'', 
                'info'=>false,
            );
            if(isset($migrated_pages_indexed[$content_page['id']])){
				
                $migrated_page = $migrated_pages_indexed[$content_page['id']];
                $migrated_page_info = self::get_migrated_page_info($migrated_page['page_id']);
                $migrated_page['info'] = $migrated_page_info; 
				$migrated_page['cat_str'] = 'טופס ברירת המחדל של האתר'; 
				
                if($migrated_page_info['biz_form']){
                    $migrated_page['cat_str'] = self::get_ilbiz_migrate_cat_str(null,$migrated_page_info['biz_form']['cat_id']);
                }
            }
            $content_pages[$key]['migrated_page'] = $migrated_page;
        }

        return array(
            'row_count'=>$page_count,
            'content_pages'=>$content_pages,
        );
    }

    protected static function get_migrated_page_info($page_id){
        $content_page = self::simple_find_by_table_name(array('id'=>$page_id),'content_pages');
        $biz_form = self::simple_find_by_table_name(array('page_id'=>$page_id),'biz_forms');

        return array(
            'content_page'=>$content_page,
            'biz_form'=>$biz_form
        );
    }

	protected static function get_ilbiz_cat_str($cat_id, $str = ""){
		if($cat_id == '0'){
            if($str == ""){
                return "קטגוריה ראשית";
            }
			return $str;
		}
		$ilbiz_db = self::getIlbizDb();
        $sql = "SELECT cat_name, father FROM biz_categories WHERE id = :cat_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetch();
		if(!$result){
			return $str;
		}
		$cat_name = utgt($result['cat_name']);
		if($str == ""){
			$str = $cat_name;
		}
		else{
			$str = $cat_name. ", ".$str;
		}
		return self::get_ilbiz_cat_str($result['father'], $str);
	}

    protected static function get_ilbiz_migrate_cat_str($ilbiz_cat_id, $cat_id = "-1",  $str = ""){
		
        if($cat_id == "-1"){
            $cat_id = self::get_migrate_cat_id($ilbiz_cat_id);
            if($cat_id == "-1"){
                return "פרסומת!!!";
            }
        }
		
		if($cat_id == '0'){
            if($str == ""){
                return "קטגוריה ראשית";
            }
			return $str;
		}
		$db = DB::getInstance();
        $sql = "SELECT label, parent FROM biz_categories WHERE id = :cat_id";
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetch();
		if(!$result){
			return $str;
		}
		if($str == ""){
			$str = $result['label'];
		}
		else{
			$str = $result['label']. ", ".$str;
		}
		return self::get_ilbiz_migrate_cat_str($ilbiz_cat_id, $result['parent'], $str);
	}

    protected static function get_migrate_cat_id($ilbiz_cat_id) {
        $db = DB::getInstance();
        $sql = "SELECT cat_id FROM migration_cat WHERE old_cat_id = :ilbiz_cat_id";
        $req = $db->prepare($sql);
        $req->execute(array('ilbiz_cat_id'=>$ilbiz_cat_id));
        $result = $req->fetch();
		if(!$result){
			return "-1";
		}
        return $result['cat_id'];
    }
	
	public static function import_page($page_id,$migration_site){
		$ilbiz_db = self::getIlbizDb();
		$sql = "select * from content_pages WHERE id = :page_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('page_id'=>$page_id));	
		$ilbiz_page_info = $req->fetch();
		
		$sql = "select * from estimate_miniSite_defualt_block WHERE type = :page_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('page_id'=>$page_id));	
		$ilbiz_form_info = $req->fetch();
		
		$page_title = utgt($ilbiz_page_info['name']);
		$page_url = str_replace(" ","-",$page_title);
		$page_url = str_replace("?","",$page_url);
		
		$videoPic = "";
		
		if($ilbiz_form_info['videoPic'] != ""){
			$videoPic = $ilbiz_form_info['videoPic'];
			$url = "http://";
			if($migration_site['old_has_ssl']){
				$url = "https://";
			}
			$url .= $migration_site['old_domain'].'/new_images/'.$ilbiz_form_info['videoPic'];
			$img = 'assets_s/'.$migration_site['site_id'].'/pages/banners/'.$ilbiz_form_info['videoPic'];
			
			$dir_path = 'assets_s/'.$migration_site['site_id']."/";
			if( !is_dir($dir_path)){
				$oldumask = umask(0) ;
				$mkdir = @mkdir( $dir_path, 0755 ) ;
				umask( $oldumask ) ;
			}
			$dir_path = $dir_path."pages/";
			if( !is_dir($dir_path)){
				$oldumask = umask(0) ;
				$mkdir = @mkdir( $dir_path, 0755 ) ;
				umask( $oldumask ) ;
			}
			$dir_path = $dir_path."banners/";
			if( !is_dir($dir_path)){
				$oldumask = umask(0) ;
				$mkdir = @mkdir( $dir_path, 0755 ) ;
				umask( $oldumask ) ;
			}
			file_put_contents($img, file_get_contents($url));
		}
		
		$new_page_info = array(
			"site_id"=>$migration_site['site_id'],
			"title"=>$page_title,
			"priority"=>$ilbiz_page_info['place'],
			"visible"=>$ilbiz_page_info['hide_page'] == '1'? '0' : '1', //hafuch - hidden vs visible
			"description"=>$ilbiz_page_info['summary'],
			"content"=>$ilbiz_page_info['summary'],
			"meta_title"=>$page_title,
			"right_banner"=>$videoPic,
			"meta_description"=>$ilbiz_page_info['description'],
			"meta_keywords"=>$ilbiz_page_info['keywords'],
			"link"=>$page_url,
		);
		
		if($new_page_info['content']!= ""){
			$new_page_info['content'] = utgt($new_page_info['content']);
		}
		if($new_page_info['description']!= ""){
			$new_page_info['description'] = utgt($new_page_info['description']);
		}
		if($new_page_info['meta_description']!= ""){
			$new_page_info['meta_description'] = utgt($new_page_info['meta_description']);
		}
		if($new_page_info['meta_keywords']!= ""){
			$new_page_info['meta_keywords'] = utgt($new_page_info['meta_keywords']);
		}
		$new_page_id = self::simple_create_by_table_name($new_page_info, 'content_pages');
		
		
		$cat_id = '0';
		if($ilbiz_form_info['primeryCat'] != "" && $ilbiz_form_info['primeryCat'] != '0'){
			$cat_id = $ilbiz_form_info['primeryCat'];
		}
		if($ilbiz_form_info['subCat'] != "" && $ilbiz_form_info['subCat'] != '0'){
			$cat_id = $ilbiz_form_info['subCat'];
		}
		if($ilbiz_form_info['cat_spec'] != "" && $ilbiz_form_info['cat_spec'] != '0'){
			$cat_id = $ilbiz_form_info['cat_spec'];
		}
		
		$new_cat_id = self::get_migrate_cat_id($cat_id);
		
		$new_cat_str = self::get_ilbiz_migrate_cat_str(null, $new_cat_id);
		
		
		if($ilbiz_form_info['input_remove'] == ""){
			$ilbiz_form_info['input_remove'] = "";
		}
		$new_form_info = array(
			'site_id'=>$migration_site['site_id'],
			'page_id'=>$new_page_id,
			'page_id'=>$new_page_id,
			'title'=>$ilbiz_form_info['top_form_headline'],
			'cat_id'=>$new_cat_id,
			'active'=>'1',
			'thanks_pixel'=>$ilbiz_form_info['thanksPixel'],
			'thanks_redirect'=>$ilbiz_form_info['thanksRedirect'],
			'input_remove'=>$ilbiz_form_info['input_remove'],
			'add_email'=>$ilbiz_form_info['addEmail'],
			'limit_by_cities'=>$ilbiz_form_info['limit_cat_by_cities'],
			'bill_type'=>$ilbiz_form_info['bill_free'],
		);
		
		if($new_form_info['title']!= ""){
			$new_form_info['title'] = utgt($new_form_info['title']);
		}
		
		$new_form_id = self::simple_create_by_table_name($new_form_info, 'biz_forms');
		
		if($ilbiz_form_info['content'] != ""){
			$form_block = array(
				'site_id'=>$migration_site['site_id'],
				'page_id'=>$new_page_id,
				'label'=>'imported-form',
				'content'=>utgt($ilbiz_form_info['content']),
				'css_class'=>'mgrt mgrt-frm',
				'priority'=>'10',
			);
			
			self::simple_create_by_table_name($form_block, 'content_blocks');
		}
		
		$page_block = array(
			'site_id'=>$migration_site['site_id'],
			'page_id'=>$new_page_id,
			'label'=>'imported-main',
			'content'=>$ilbiz_page_info['content'],
			'css_class'=>'mgrt mgrt-main',
			'priority'=>'10',
		);
		if($page_block['content'] != ""){
			$page_block['content'] = utgt($page_block['content']);
			$page_block['content'] = str_replace("white-cube","c-block",$page_block['content']);
		}
		self::simple_create_by_table_name($page_block, 'content_blocks');
		
		$migrate_version = "1.2";
		
		$migrate_info = array(
			"site_id"=>$migration_site['site_id'],
			"migrated"=>"1",
			"old_page_id"=>$page_id,
			"page_id"=>$new_page_id,
			"version"=>$migrate_version,
			"has_form"=>'1'
		);
		self::simple_create_by_table_name($migrate_info, 'migration_page');
		return array(
			'page_id'=>$new_page_id,
			'version'=>$migrate_version,
			'cat_str'=>$new_cat_str
		);
	}

	public static function delete_migrated_page($page_id,$migration_site){
		
		$db = Db::getInstance();
		$execute_arr = array('page_id'=>$page_id);
		
		$sql = "SELECT * FROM migration_page WHERE page_id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$result = $req->fetch();
		
		$return_array = $result;
		
		$sql = "SELECT right_banner FROM content_pages WHERE id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$result = $req->fetch();
		if($result){
			if($result['right_banner'] != ""){
				$img = '/assets_s/'.$migration_site['site_id'].'/pages/banners/'.$result['right_banner'];
				if(file_exists($img)){  
					unlink($img);
				}
			}
		}
		
		$sql = "DELETE FROM content_pages WHERE id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$sql = "DELETE FROM content_blocks WHERE page_id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$sql = "DELETE FROM biz_forms WHERE page_id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$sql = "DELETE FROM page_style WHERE page_id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		$sql = "DELETE FROM migration_page WHERE page_id = :page_id";
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
		
		return $return_array;
	}	
}
?>