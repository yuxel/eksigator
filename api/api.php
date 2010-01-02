<?
header("Cache-Control: no-cache, must-revalidate");

//error_reporting(0);
//ini_set("display_item",0);

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
    } 
}
else{
    $eksigator->toJson(array("message"=>"AUTH_FAILED") );
}
