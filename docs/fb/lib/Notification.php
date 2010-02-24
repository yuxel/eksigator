<?php

class Notification extends ModuleBase
{
    function sendNotificationToUser($uid) {
            $message = "Takip ettiğiniz başlıklara yeni entry'ler girildi. ";
            $message .= "Değişen başlıkları görmek için <a href=\"".$this->parent->fbUrl."\">tıklayın</a>";
            $this->facebook->api_client->notifications_send($uid, $message, 'app_to_user');
    }


}
