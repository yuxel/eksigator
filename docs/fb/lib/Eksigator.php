<?php

class Eksigator
{


    public function __construct($api_key, $secret, $generate_session_secret=false) {
        $this->facebook = new Facebook($api_key, $secret, $generate_session_secret=false);
        
        $this->parseUrl();

        $this->urlHandler();
    }


    function parseUrl(){
        $this->actions = explode("/", $_GET['q']);
    }



    function urlHandler() {
   
        $this->facebookUser = $this->facebook->require_login();

        if(!$this->getUserData($this->facebookUser)){
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
        $this->module->setAction( $this->actions[0] );
        $this->module->initView();
        $this->module->run();
        $this->module->printPage(); 
    }


    function getUserData($userId) {
        return true;
        return false;
    }


}
