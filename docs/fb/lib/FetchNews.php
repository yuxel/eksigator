<?php

class FetchNews extends ModuleBase
{

    function run(){

        $news = $this->getTitleStatus();

        $this->view->assign("news", $news);

        $eksiUrl = $this->parent->eksiUrl;

        $this->view->assign("eksiUrl",$eksiUrl);

        $this->rightContent = $this->view->fetch("news.html");
    }


    function getTitleStatus(){

        $email = $this->parent->userData['email'];
        $apiKey = $this->parent->userData['apiKey'];

        $datas = $this->parent->getData($email, $apiKey);

        foreach((array)$datas as $data) {
            $status = $data->status;


            $hashArray = array($email, $apiKey, $data->title);
            $hashSerialize = serialize($hashArray);
            $hash64 = base64_encode($hashSerialize);

            $data->url = $hash64;

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
