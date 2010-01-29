<?php

require_once 'facebookClient/facebook.php';
require_once "../php/3rdParty/smarty/libs/Smarty.class.php";

$appapikey = '62fff396d245acbc100fb0f4d1e37e60';
$appsecret = '65af76bcd01839ca44ff73cf255ee0aa';


$view = new Smarty();

$facebook = new Facebook($appapikey, $appsecret);
$facebook_user_id = $facebook->require_login();

$url = "http://api.eksigator.com/demo/demo/getList";
$data = file_get_contents ( $url );

$array = json_decode ($data);


foreach($array as $item) {
    if($item->status == 1) {
        $updated[$item->title] = $item;
    }
    else{
        $notUpdated[$item->title] = $item;
    }
}


foreach($updated as $item) {
    echo $item->title."<br/>";
}

echo "<hr/>";

foreach($notUpdated as $item) {
    echo $item->title."<br/>";
}




//$facebook->api_client->notifications_send($user_id, 'Eksigator\'a hos geldiniz, takip ettiginiz basliklara yeni bir bildiri gelirse buradan takip edebilirsiniz', 'app_to_user');

// Greet the currently logged-in user!
echo "<p>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</p>";

// Print out at most 25 of the logged-in user's friends,
// using the friends.get API method
echo "<p>Friends:";
$friends = $facebook->api_client->friends_get();
$friends = array_slice($friends, 0, 25);
foreach ($friends as $friend) {
  echo "<br>$friend";
}
echo "</p>";
