<?php


$system_config = array(
    'system_name'=>'master_admin',

    'site_title'=>'ניהול ראשי - איי אל ביז',
    'session_prefix'=>'m_admin_',

    'error_controller'=>'pages',
    'error_action'=>'error',

    'home_controller'=>'tasks',
    'home_action'=>'list',

    'email_sender'=>'info@ilbiz.co.il',
    'email_sender_name'=>'מנהל הרשת',

    'override_models'=>array('test'),
    'main_module'=>'master_admin',
);
