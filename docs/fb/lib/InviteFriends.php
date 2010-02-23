<?php

class InviteFriends extends ModuleBase
{

    function run(){

       $content = "<fb:name uid=\"".$user."\" firstnameonly=\"true\" shownetwork=\"false\"/> has started using <a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a> and thought it's so cool even you should try it out!\n".
        "<fb:req-choice url=\"".$this->parent->fbUrl."\" label=\"Put ".$app_name." on your profile\"/>"; 

       
        $this->view->assign("reqContent", $content); 
    
        $this->rightContent = $this->view->fetch ("invite.html");
    }


} 
