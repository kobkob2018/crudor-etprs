<?php


$config = array(
    'system_name'=>'mymvc',
    'cash_version'=>'1.8',

    'db_host'=>'', //update in  a_core/secret.php that is in gitignore
    'db_database'=>'', //update in  a_core/secret.php that is in gitignore
    'db_user'=>'', //update in  a_core/secret.php that is in gitignore
    'db_password'=>'', //update in  a_core/secret.php that is in gitignore

    'default_system'=>'sites',
    'site_title'=>'רשת איי אל ביז',
    'session_prefix'=>'mymvc_',
    'base_url'=>'',
    'base_url_dir'=>'',

    'master_domain'=>'webushka.com',

    'master_url'=>'http://webushka.com',
    'error_controller'=>'pages',
    'error_action'=>'error',

    'handle_access_default'=>'login_only',
    'home_controller'=>'pages',
    'home_action'=>'home',

    'email_sender'=>'ilan@il-biz.co.il',
    'email_sender_name'=>'מנהל הרשת',
    'is_mobile'=>false,

    'a_core_models'=>array('userLogin'
        ,'users'
        ,'user_rolls'
        ,'systemMessages'
        ,'global_settings'
        ,'sites'
        ,'biz_categories'
        ,'cities'
        ,'user_pending_emails'
        ,'test'),
    'override_models'=>array(),
    'access_module'=>'main',
    /*
    //to change configuration to main symlinks, change following 2 lines 
    'sites_build_format'=>'symlinks',
    #'domains_path'=>'/domains',
    */
    'sites_build_format'=>'pointer_to_main',
    /*
    // the real domain path
    'domains_path'=>'///home/ilan123/domains/',
    */
    'domains_path'=>'domains',
    'cookie_prefix'=>'crudor',
    'mode'=>'live', //in secret dev invierment can change to dev
);

if(!isset($is_cron_master)){
    $config['base_url'] = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
}

else{
    $config['base_url'] = $config['master_url'];
}

if(isset($_SERVER["HTTP_USER_AGENT"])){
    $config['is_mobile'] = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
else{
    $config['is_mobile'] = false;
}



