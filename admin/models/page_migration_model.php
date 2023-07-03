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
        $sql = "select * from content_pages WHERE unk = :unk";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('domain'=>$domain));
        $result = $req->fetchAll();
        $content_pages = array();
        $formated_params = array(
            'name', 'content', 'keywords', 'description', 'redirect_301'
        );
        if($result){
            foreach($result as $content_page){
                foreach($formated_params as $param)
                $content_page[$param] = utgt($content_page[$param]);
                $content_pages[] = $content_page;
            }
        }

        $migrated_pages = self::get_list(array($site_id=>$site_migration['site_id']));

        $migrated_pages_indexed = Helper::eazy_index_arr_by('old_page_id',$migrated_pages);

        foreach($content_pages as $key=>$content_page){
            $migrated_page = array(
                'migrated'=>"no",
                'id'=>''
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
}
?>