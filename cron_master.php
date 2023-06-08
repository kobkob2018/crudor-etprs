<?php 
chdir(dirname(__FILE__));
// http://usites.com/cron_master.php?checktime=02/22/2023%2024:00
$init_request = array(
    'system'=>'master_admin',
    'controller'=>'cron_master',
    'action'=>'map_cron_actions'
);
$is_cron_master = true;
require_once('index.php');