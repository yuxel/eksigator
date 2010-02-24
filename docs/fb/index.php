<?php

ini_set("display_errors",1);
error_reporting(E_ALL ^ E_NOTICE);

require_once 'conf/application.php';

require_once THIRD_PARTY_DIR."/smarty/libs/Smarty.class.php";
require_once 'facebookClient/facebook.php';
require_once 'lib/Eksigator.php';
require_once 'lib/Db.php';
require_once 'lib/ModuleBase.php';


$eksigator = new Eksigator($apiKey, $apiSecret);

if($argv[0] == "cron") {
    $uid = "525202689";
    $eksigator->api_client->notifications_send($uid, 'some info', 'app_to_user');
}
