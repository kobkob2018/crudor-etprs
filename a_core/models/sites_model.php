<?php
  class Sites extends TableModel{

    protected static $main_table = 'sites';

    protected static $sites_by_domain = array();
    protected static $sites_by_id = array();
    protected static $sites_arr = array();

    public static function get_current_site(){
        if(!isset(self::$sites_arr['current'])){
            if(session__isset("site_id")){
                self::$sites_arr['current'] = self::get_by_id(session__get("site_id"));
				if(self::$sites_arr['current']['domain'] != $_SERVER["HTTP_HOST"]){
					session__unset("site_id");
					header('Location: http://'.$_SERVER["HTTP_HOST"]);
				}
            }
            else{              
                $current_site = self::get_by_domain($_SERVER["HTTP_HOST"]);
                self::$sites_arr['current'] = $current_site;
                if($current_site){
                    self::$sites_arr['current'] = $current_site;
                    session__set("site_id",self::$sites_arr['current']['id']);
                }
            }
        }
        return self::$sites_arr['current'];
    }

    public static function get_by_id($site_id, $select_params = "*"){
        if(!isset(self::$sites_by_id[$select_params])){
            self::$sites_by_id[$select_params] = array();
        }
        if(!isset(self::$sites_by_id[$select_params][$site_id])){
            self::$sites_by_id[$select_params][$site_id] = self::find(array('id'=>$site_id),$select_params);
        }
        return self::$sites_by_id[$select_params][$site_id];
    }

    public static function get_by_domain($domain_name, $select_params = "*"){
        if(!isset(self::$sites_by_domain[$select_params])){
            self::$sites_by_domain[$select_params] = array();
        }
        if(!isset(self::$sites_by_domain[$select_params][$domain_name])){
            self::$sites_by_domain[$select_params][$domain_name] = self::simple_find(array('domain'=>$domain_name),$select_params);
        }
        return self::$sites_by_domain[$select_params][$domain_name];
    }

    public static function get_user_site_list(){
        $user = UserLogin::get_user();
        if(!$user){
            return false;
        }
        $execute_arr = array('user_id'=>$user['id']);
        $sql = "SELECT st.* FROM sites st LEFT JOIN user_sites us ON us.site_id = st.id WHERE us.user_id = :user_id  AND status != 0";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        return $req->fetchAll();
    }   

    public static function get_user_workon_site(){
        $user = UserLogin::get_user();
        if(!$user){
            return false;
        }
        if(!session__isset('workon_site')){
            return false;
        }
        if(isset(self::$sites_arr['workon'])){
            return self::$sites_arr['workon'];
        }

        $site_id = session__get('workon_site');
        $execute_arr = array('user_id'=>$user['id'],'site_id'=>$site_id,'all'=>'*');
        $sql = "SELECT st.*, us.roll as 'admin_roll' 
                FROM sites st 
                LEFT JOIN user_sites us 
                ON us.site_id = st.id 
                WHERE (us.user_id = :user_id AND us.site_id = :site_id AND us.status != 0)
                OR (us.user_id = :user_id AND us.site_id = :all)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $site = $req->fetch();
        if(is_array($site)){
            $site['url'] = self::get_site_url($site);
        }
        $site_url = self::get_site_url($site);
        self::$sites_arr['workon'] = $site;
        return self::$sites_arr['workon'];
    }

    public static function check_user_workon_site($site_id){
        $user = UserLogin::get_user();
        if(!$user){
            return false;
        }

        $execute_arr = array('user_id'=>$user['id'],'site_id'=>$site_id,'all'=>'*');
        $sql = "SELECT * FROM user_sites 
            WHERE (user_id = :user_id AND site_id = :site_id  AND status != 0)
            OR (user_id = :user_id AND site_id = :all)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetch();
        if($result){
            session__set('workon_site',$site_id);
            return $site_id;
        }
        return false;
    }   

    public static function get_user_workon_site_asset_dir(){
        $workon_site = self::get_user_workon_site();
        return self::get_site_asset_dir($workon_site);
    }

    public static function get_current_site_asset_dir(){
        $current_site = self::get_current_site();
        return self::get_site_asset_dir($current_site);
    }

    public static function get_site_asset_dir($site){
        $return_array = array(
            'url'=>'',
            'path'=>'',
            'outer_url'=>''
        );

        $site_url = self::get_site_url($site);

        $return_array['path'] = 'assets_s/'.$site['id'].'/';
        $return_array['url'] = '/assets_s/'.$site['id'].'/';
        $return_array['outer_url'] = $site_url.'/assets_s/'.$site['id'].'/';
        if(!is_dir('assets_s')){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s', 0755 ) ;
            umask( $oldumask ) ;
        }
        if(!is_dir('assets_s/'.$site['id'])){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site['id'], 0755 ) ;
            umask( $oldumask ) ;
        }
        return $return_array;
    }

    public static function get_site_url($site){
        $site_url_http_s = 'http://';
        if($site['is_secure']){
            $site_url_http_s = 'https://';
        }
        $site_url = $site_url_http_s.$site['domain'];
        if(get_config('base_url_dir') != ''){
            $site_url.='/'.get_config('base_url_dir');
        }
        return $site_url;
    }

    public static $asset_mapping = array(
        'logo'=>'site',
        'favicon'=>'site',
        'colors_css'=>'style'
    );
  }
?>