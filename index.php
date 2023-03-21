<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(!isset($init_request)){
    $init_request = array();
    if(isset($_GET['controller'])){
        $init_request['controller'] = $_GET['controller'];
    }
    if(isset($_GET['action'])){
        $init_request['action'] = $_GET['action'];
    }
    if(isset($_REQUEST['system'])){
        $init_request['system'] = $_REQUEST['system'];
    }
}



require_once('a_core/config.php');
require_once('a_core/secret.php');
require_once('a_core/connection.php');
require_once('a_core/models/model.php');
require_once('a_core/models/tableModel.php');
require_once('a_core/controllers/controller.php');
require_once('a_core/controllers/crudController.php');
require_once('a_core/modules/module.php');
require_once('a_core/helper.php');
require_once('a_core/helpers/helper.php');
require_once('a_core/helpers/view.php');



$system = isset($init_request['system'])? $init_request['system']: get_config('default_system');

//now merge a_core config with system config!!
require_once($system."/config.php");
foreach($system_config as $key=>$value){
    $config[$key] = $value;
}
//here is where the action begin

require_once('a_core/routes.php');



 
?>