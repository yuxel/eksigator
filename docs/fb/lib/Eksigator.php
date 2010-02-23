<?php

class Eksigator
{


    public function __construct($api_key, $secret, $generate_session_secret=false) {
        $this->facebook = new Facebook($api_key, $secret, $generate_session_secret=false);

        $this->apiUrl = "http://api.eksigator.com/";
        $this->fbUrl = "http://apps.facebook.com/eksigator/";
        $this->eksiUrl = "http://sozluk.sourtimes.org/";
        
        $this->db = new Db();

        $this->parseUrl();

        $this->urlHandler();

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


    function urlHandler() {
   
        $this->facebookUser = $this->facebook->require_login();

        $this->userData = $this->getUserData($this->facebookUser);

        if( !$this->userData ){
            $module = "Register";
        } 
        else{
            if( $this->actions[0] == "ayarlar" ) {
                $module = "UserSettings";
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

