<?php
  class Page_migration extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'page_migration';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function get_old_site_page_list($site_migration){


        $ilbiz_db = self::getIlbizDb();
        $sql = "select page.*, form.content as form_content, cat_spec, subCat, primeryCat from content_pages page 
		LEFT JOIN estimate_miniSite_defualt_block form ON form.type= page.id
		WHERE page.unk = :unk ";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$site_migration['old_unk']));
        $result = $req->fetchAll();
        $content_pages = array();
        $formated_params = array(
            'name', 'content', 'keywords', 'description', 'redierct_301'
        );
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
				$cat_str_arr = array();
				if(!isset($cat_str_arr[$cat_id])){
					$cat_str_arr[$cat_id] = self::get_ilbiz_cat_str($cat_id);
				}
				$cat_str = $cat_str_arr[$cat_id];
				$content_page['cat_id'] = $cat_id;
				$content_page['cat_str'] = $cat_str;
                $content_pages[] = $content_page;
            }
        }
		
        $migrated_pages = self::get_list(array('site_id'=>$site_migration['site_id']));

        $migrated_pages_indexed = Helper::eazy_index_arr_by('old_page_id',$migrated_pages);

        foreach($content_pages as $key=>$content_page){
            $migrated_page = array(
                'migrated'=>"no",
                'id'=>'',
                'page_id'=>'',
                'version'=>'',
                'has_form'=>'no',
            );
            if(isset($migrated_pages_indexed[$content_page['id']])){
                $migrated_page = $migrated_pages_indexed[$content_page['id']];
            }
            $content_pages[$key]['migrated_page'] = $migrated_page;
        }

        return $content_pages;
    }

	protected static function get_ilbiz_cat_str($cat_id, $str = ""){
		if($cat_id == '0'){
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
}
?>