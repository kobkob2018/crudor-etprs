<?php 

require_once("a_core/secret.php");

echo "Your CPIP adress is: ".$_SERVER['HTTP_CF_CONNECTING_IP'];

echo "Your IP adress is: ".$_SERVER['REMOTE_ADDR'];
/*

send_email_test();
exit("ok");


function send_email_test(){
    require_once('a_core/helpers/smtp_handler.php');
    send_email_with_smtp();
}

*/
?>