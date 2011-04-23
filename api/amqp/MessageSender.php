<?php
/**
 * Eksigator AMQP 
 *
 * @author Osman Yuksel < yuxel |ET | sonsuzdongu |DOT| com >
 */

//path to your amqp-php library
require_once('php_amqplib/amqp.inc');

class MessageSender {
    //set configs
    function __construct() {
        $this->rabbitMQConf = array("host"=>"localhost",
                                    "port"=>"5672",
                                    "user"=>"guest",
                                    "pass"=>"guest",
                                    "virtualHost"=>"/");


        $this->queueConf = array("exchange"=>"bulletin");
    }

    //send  $text $to someone with a $subject
    function send($url, $hash) {
        try{
            $conn = new AMQPConnection($this->rabbitMQConf['host'], 
                                       $this->rabbitMQConf['port'],
                                       $this->rabbitMQConf['user'],
                                       $this->rabbitMQConf['pass']);

            $channel = $conn->channel();

            $channel->access_request($this->rabbitMQConf['virtualHost'], false, false, true, true);

            $url = str_replace("http://www.eksisozluk.com","", $url);

            $fetchData = array("url"=>$url,
                               "hash"=>$hash);

            $fetchDataToJson = json_encode($fetchData);

            $msg = new AMQPMessage($fetchDataToJson, array('content_type' => 'text/plain'));
            $channel->basic_publish($msg, $this->queueConf['exchange']);

            $channel->close();
            $conn->close();

            return true;
        }
        catch(Exception $e) {
            echo "Something went wrong ".$e->getMessage();
        }

    }
}

/*
$fetchSender = new MessageSender();

$url = "http://www.eksisozluk.com/show.asp?t=the+big+bang+theory&i=2099999999";
$hash = "cache/xxxxx";

if ( $fetchSender->send($url, $hash) ) {
   echo "Message sent to queue"; 
}
*/
