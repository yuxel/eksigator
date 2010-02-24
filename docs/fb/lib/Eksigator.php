<?php

class Eksigator
{


    public function __construct($api_key, $secret, $cronInterval=false) {
        $this->facebook = new Facebook($api_key, $secret, $generate_session_secret=false);

        $this->apiUrl = "http://api.eksigator.com/";
        $this->fbApiUrl = "http://fb.eksigator.com/";
        $this->fbUrl = "http://apps.facebook.com/eksigator/";
        $this->eksiUrl = "http://sozluk.sourtimes.org/";
        
        $this->db = new Db();

        if(!$cronInterval) {

            $this->parseUrl();

            $this->urlHandler();

        }
        else{ //cron
            $uid = 525202689;
            $this->notificaton = new Notification();
            $this->notificaton->sendNotificationToUser($uid); 
        }

    }


    function parseUrl(){
        $this->actions = explode("/", $_GET['q']);
    }



    function getData($email, $apiKey) {
        $dataUrl = $this->apiUrl.$email."/".$apiKey."/getList";
        $dataText = file_get_contents($dataUrl);

        $dataJson = json_decode($dataText);

        if( $dataJson->message == "AUTH_FAILED") {
            throw new Exception("Auth failed");
        }

        return $dataJson;
    }


    function installRemoveLog($status) {
        
        $id = $_REQUEST['fb_sig_user'];

        $query = "insert into facebook_log (fb_id, status)
                  values ( $id, $status )
                  on duplicate key
                  update status = values (status)"; 

        $this->db->query ( $query );

        exit;

    }


    function setItemAsRead($hashed) {

        $decoded = base64_decode($hashed);

        $array = unserialize($decoded);

        $email = $array[0];
        $apiKey = $array[1];
        $title = $array[2];

        $addUrl = $this->apiUrl.$email."/".$apiKey."/setItemAsRead/".$title;
        file_get_contents($addUrl);

        $eksiUrl = $this->eksiUrl."show.asp?t=".$title;

        var_dump($eksiUrl);
        echo $eksiUrl;
        exit;

        $this->facebook->redirect($eksiUrl);
        exit;

    }




    function urlHandler() {
  

        if( $this->actions[0] == "userInstalled" ) {
            $this->installRemoveLog(1);
        }
        elseif ($this->actions[0] == "userRemoved" ) {
            $this->installRemoveLog(0);
        }
        elseif($this->actions[0] == "redirect") {
           $this->setItemAsRead( $this->actions[1] );
        }

        $this->facebookUser = $this->facebook->require_login();

        $this->userData = $this->getUserData($this->facebookUser);

        if( !$this->userData ){
            $module = "Register";
        } 
        else{
            $this->userLoggedIn = true;
            if( $this->actions[0] == "ayarlar" ) {
                $module = "UserSettings";
            }
            else if( $this->actions[0] == "yardim" ) {
                $module = "Help";
            }
            else if( $this->actions[0] == "liste" ) {
                $module = "TitleList";
            }
            else if( $this->actions[0] == "davet" ) {
                $module = "InviteFriends";
            }
            else {
                $module = "FetchNews";
            }
        }

        require_once( "lib/". $module. ".php" );
        $this->module = new $module();
        $this->module->name = $module;
        $this->module->setFacebookClient( $this->facebook );
        $this->module->setParent ( $this );

        $this->module->setAction( $this->actions[0] );
        $this->module->initView();
        $this->module->run();
        $this->module->printPage(); 
    }


    function getUserData($userId) {

        $userId = (int) $userId;

        $query = "SELECT fb.fb_id, u.email, u.apiKey, fb.interval
                  FROM `users` AS u, facebook AS fb
                  WHERE u.active =1
                  AND fb.eksigator_id = u.id
                  and fb.fb_id = '$userId'";


        $data = $this->db->fetch($query);

        if(!$data) {
            return false;
        }
    
        return $data[0];    
    }


}

