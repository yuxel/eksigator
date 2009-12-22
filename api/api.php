<?

//@todo this should be moved to a config file
define ('DB_HOST', "localhost" );
define ('DB_USER', "root");
define ('DB_PASS', "12345678");
define ('DB_NAME', "eksigator");

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
