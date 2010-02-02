<?php

class Eksigator
{


    public function __construct($api_key, $secret, $generate_session_secret=false) {
        $this->facebook = new Facebook($api_key, $secret, $generate_session_secret=false);
        
        $this->parseUrl();
        $this->facebookUser = $this->facebook->require_login();

        $this->view = new Smarty();
        $this->urlHandler();
    }


    function parseUrl(){
        $this->actions = explode("/", $_GET['q']);
    }



    function urlHandler() {
    
        var_dump ( $this->facebookUser );

        

        if( $this->actions[0] == "ayarlar" ) {
            $module = "UserSettings";
        }
        else if( $this->actions[0] == "davetEt" ) {
            $module = "InviteFriends";
        }
        else {
            $module = "FetchNews";
        }

        require_once( "lib/". $module. ".php" );
        $this->module = new $module();
        $this->module->setFacebookClient( $this->facebook );
    }

}
