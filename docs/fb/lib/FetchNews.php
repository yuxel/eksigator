<?php

class FetchNews extends ModuleBase
{

    function run(){

        $this->getTitleStatus();

        $this->rightContent = $this->view->fetch("news.html");
    }


    function getTitleStatus(){

        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $datas = $this->parent->getData($email, $apiKey);


        foreach($datas as $data) {

            echo $data->title."<br/>";

        }


    }



} 
