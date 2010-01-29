<?php

require_once 'conf/application.php';
$view = new Smarty();

$facebook = new Facebook($appapikey, $appsecret);
$facebook_user_id = $facebook->require_login();

$view->display("notSigned");


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
