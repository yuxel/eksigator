<?php

class FetchNews extends ModuleBase
{

    function run(){

        $news = $this->getTitleStatus();

        $this->view->assign("news", $news);

        $this->rightContent = $this->view->fetch("news.html");
    }


    function getTitleStatus(){

        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $datas = $this->parent->getData($email, $apiKey);

        foreach($datas as $data) {
            $status = $data->status;
            if($status == 1) {
                $news[1][] = $data;
            }
            else{
                $news[0][] = $data;
            }

        }

        return $news;
    }



} 
