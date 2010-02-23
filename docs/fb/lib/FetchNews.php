<?php

class FetchNews extends ModuleBase
{

    function run(){

        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        
        $data =$this->parent->getData($email, $apiKey);

        var_dump ( $data );


        $this->rightContent = $this->view->fetch("news.html");
    }




} 
