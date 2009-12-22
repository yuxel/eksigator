<?

define ('DB_HOST', "localhost" );
define ('DB_USER', "root");
define ('DB_PASS', "12345678");
define ('DB_NAME', "eksigator");



require_once("Eksigator.php");

$eksigator = new Eksigator();

$params = explode("/", $_GET['q']);

$userName = $params[0];
$apiKey = $params[1];
$function = $params[2];
$functionParam = stripslashes($params[3]);


if( $eksigator->authenticateUser($userName,$apiKey) ) {
    $eksigator->subscriptionLists =  $eksigator->getUserSubscriptionList();
    switch($function) {
        case "getList":
            $list = $eksigator->getSubscriptionStatus();
        $eksigator->toJson($list);
        break;
        case "addToList":
            $title = $functionParam;
        $addStatus = $eksigator->addToList($title);
        $list = $eksigator->getSubscriptionStatus();
        $eksigator->toJson($list);
        break;
        case "removeFromList":
            $title = $functionParam;
        $addStatus = $eksigator->removeFromList($title);
        $list = $eksigator->getSubscriptionStatus();
        $eksigator->toJson($list);
        break;
        case "setItemAsRead":
            $title = $functionParam;
        $addStatus = $eksigator->setItemAsRead($title);
        $list = $eksigator->getSubscriptionStatus();
        $eksigator->toJson($list);
        break;


    } 
}
else{
    $eksigator->toJson(array("message"=>"AUTH_FAILED") );
}
