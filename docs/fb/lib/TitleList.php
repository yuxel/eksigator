<?php

class TitleList extends ModuleBase
{

    function run(){
        $titles = $this->getTitles();

        $this->view->assign("titles", $titles);
        $this->rightContent = $this->view->fetch ("list.html");
    }

    function getTitles() {

        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $datas = $this->parent->getData($email, $apiKey);

        return $datas;
    }


} 
