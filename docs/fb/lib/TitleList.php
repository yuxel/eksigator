<?php

class TitleList extends ModuleBase
{

    function run(){

        if($_POST['newTitle']) {
            $title = $_POST['newTitle'];
            $this->addToList($title);
        }
         

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


    function addToList($title) {
        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $addUrl = $this->parent->apiUrl.$email."/".$apiKey."/addToList/".$title;
        file_get_contents($addUrl);

    }

    function removeFromlist($title) {
        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $addUrl = $this->parent->apiUrl.$email."/".$apiKey."/removeFromList/".$title;
        file_get_contents($addUrl);

    }


} 
