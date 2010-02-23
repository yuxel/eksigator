<?php

class InviteFriends extends ModuleBase
{

    function run(){

       $content = "<fb:req-choice url=\"http://apps.facebook.com/eksigator\" label=\"Put this on your profile\"/>"; 


       $content = htmlentities($content,ENT_COMPAT,'UTF-8');
        

        $this->view->assign("reqContent", $content); 
    
        $this->rightContent = $this->view->fetch ("invite.html");
    }


} 
