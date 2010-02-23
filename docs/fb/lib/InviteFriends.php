<?php

class InviteFriends extends ModuleBase
{

    function run(){

       $content = "<fb:req-choice url=\"http://apps.facebook.com/eksigator\" label=\"Put this on your profile\"/>"; 


       $content = htmlspecialchars($content);

       var_dump ( $content );
        

        $this->view->assign("reqContent", $content); 
    
        $this->rightContent = $this->view->fetch ("invite.html");
    }


} 
