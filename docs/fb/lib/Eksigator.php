<?php

class Eksigator
{


    public function __construct($api_key, $secret, $generate_session_secret=false) {
        $this->facebook = new Facebook($api_key, $secret, $generate_session_secret=false);

        $this->apiUrl = "http://api.eksigator.com/";
        
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

        if($this->getUserData($this->facebookUser)){
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
        $this->module->setFacebookClient( $this->facebook );
        $this->module->setParent ( $this );

        $this->module->setAction( $this->actions[0] );
        $this->module->initView();
        $this->module->initDb();
        $this->module->run();
        $this->module->printPage(); 
    }


    function getUserData($userId) {

        var_dump ( $userId );

        return true;
        return false;
    }


}

