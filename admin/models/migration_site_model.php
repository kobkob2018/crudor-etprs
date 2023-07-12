<?php
  class Migration_site extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_site';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static $fields_collection = array(

        'old_domain'=>array(
            'label'=>'דומיין של אתר במערכת הישנה',
            'type'=>'text',
            'validation'=>'required'
        ),

    );

    public static function get_old_site_data_by_domain($domain){
        $ilbiz_db = self::getIlbizDb();
        $sql = "select id, domain, unk, name, has_ssl from users WHERE domain = :domain";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('domain'=>$domain));
        $result = $req->fetch();
        $has_ssl = '0';
        if($result['has_ssl'] != '0'){
            $has_ssl = '1';
        }
        if($result){
			$title = utgt($result['name']);
            return array(
                'domain'=>$result['domain'],
                'unk'=>$result['unk'],
                'site_id'=>$result['id'],
                'title'=>$title,
                'has_ssl'=>$has_ssl
            );
        }
        return false;
    } 

    public static function get_site_by_unk($unk){
        $ilbiz_db = self::getIlbizDb();
        $sql = "select id, domain, unk, name, has_ssl from users WHERE unk = :unk";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$unk));
        $result = $req->fetch();
        return $result;
    }

    public static function fix_quotes_images(){
        $sites_by_unk = array();
        $all_quotes = Quotes::get_list(array(),'unk, id, image');
        $all_images = array();
        foreach($all_quotes as $quote){
            if($quote['image'] == '' || $quote['unk'] == ''){
                continue;
            }
            if(!isset($sites_by_unk[$quote['unk']])){
                $sites_by_unk[$quote['unk']] = self::get_site_by_unk($quote['unk']);
            }
            $site = $sites_by_unk[$quote['unk']];
            $image_url = "http://";
            if($site['has_ssl']){
                $image_url = "https://";
            }
            $image_url.= $site['domain']."/user_service_offers/".$quote['image'];
            $new_image_url = "assets_s/2/quotes/".$quote['image'];
            if(!file_exists($new_image_url)){

                file_put_contents($new_image_url, file_get_contents($image_url));
                echo "<br/>$image_url<br/><img src='$image_url' width='100px' height='100px' />";
            }

            echo "<br/>/$new_image_url<br/><img src='/$new_image_url' width='100px' height='100px' />";
            exit();

            echo "<br/>$image_url<br/><img src='$image_url' width='100px' height='100px' />";
        }
        echo "<h3>done</h3>";
    }
}
?>