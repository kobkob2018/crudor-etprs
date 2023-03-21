<?php


$system_config = array(
    'system_name'=>'ilbiz_admin',

    'site_title'=>'ניהול אתר - איי אל ביז',
    'session_prefix'=>'admin_',

    'error_controller'=>'pages',
    'error_action'=>'error',

    'home_controller'=>'tasks',
    'home_action'=>'all',

    'email_sender'=>'info@ilbiz.co.il',
    'email_sender_name'=>'מנהל הרשת',

    'override_models'=>array('test'),
    'main_module'=>'admin',
);
