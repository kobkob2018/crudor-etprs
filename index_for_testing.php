<?php 
/*
https://il-biz.com/myleads/yaad_return/ok/?Id=115702509&CCode=0&Amount=1&ACode=0318133&Order=31&Fild1=%E9%F2%F7%E1%20%E0%E1%F8%E4%ED&Fild2=yacov.avr%40gmail.com&Fild3=&Bank=2&Payments=1&UserId=031433006&Brand=1&Issuer=1&L4digit=5442&street=%F4%F8%F5%20%E9%EC%2029&city=%E1%E0%F8%20%F9%E1%F2&zip=123123&cell=&Coin=1&Tmonth=08&Tyear=2027&Info=%F7%F0%E9%E9%FA%201%20%EC%E9%E3%E9%ED&errMsg=%FA%F7%E9%EF%20(0)&Hesh=8024&UID=23050111585828601936320&SpType=0&BinCard=532610

https://mylove.com/myleads/yaad_return/ok/?Id=115702509&CCode=0&Amount=1&ACode=0318133&Order=31&Fild1=%E9%F2%F7%E1%20%E0%E1%F8%E4%ED&Fild2=yacov.avr%40gmail.com&Fild3=&Bank=2&Payments=1&UserId=031433006&Brand=1&Issuer=1&L4digit=5442&street=%F4%F8%F5%20%E9%EC%2029&city=%E1%E0%F8%20%F9%E1%F2&zip=123123&cell=&Coin=1&Tmonth=08&Tyear=2027&Info=%F7%F0%E9%E9%FA%201%20%EC%E9%E3%E9%ED&errMsg=%FA%F7%E9%EF%20(0)&Hesh=8024&UID=23050111585828601936320&SpType=0&BinCard=532610
*/
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