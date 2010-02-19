<?

//disable caching
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

error_reporting(0);
ini_set("display_item",0);

require_once("Eksigator.php");

$eksigator = new Eksigator();

//parse params from url
$params = explode("/", $_GET['q']);

$userName = $params[0];
$apiKey = $params[1];
$function = $params[2];
$functionParam = stripslashes($params[3]);


//try to authenticate
if( $eksigator->authenticateUser($userName,$apiKey) ) {
    
    switch($function) {

        case "getList":
            $list = $eksigator->getSubscriptionStatus();
            $eksigator->toJson($list);
        break;

        case "addToList":
            $title = $functionParam;
            $eksigator->addToList($title);
            $list = $eksigator->getSubscriptionStatus();
            $eksigator->toJson($list);
            break;

        case "removeFromList":
            $title = $functionParam;
            $eksigator->removeFromList($title);
            $list = $eksigator->getSubscriptionStatus();
            $eksigator->toJson($list);
            break;

        case "setItemAsRead":
            $title = $functionParam;
            $eksigator->setItemAsRead($title);
            $list = $eksigator->getSubscriptionStatus();
            $eksigator->toJson($list);
            break;

        case "fetchAll":
            $eksigator->fetchAll();
            break;

    } 
}
//may be runned from command line interface, cron jobs will do that
elseif( $argv[1]=="fetchAll" ){
    $eksigator->fetchAll();
}
else {
    $eksigator->toJson(array("message"=>"AUTH_FAILED") );
}
