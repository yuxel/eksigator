<?php

class Eksigator extends Facebook
{


    public function __construct($api_key, $secret, $generate_session_secret=false) {
        parent::__construct($api_key, $secret, $generate_session_secret=false);

        $this->parseUrl();
    }


    function parseUrl(){
        var_dump ($_GET);
    }



}
