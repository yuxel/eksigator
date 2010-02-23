<?php

class InviteFriends extends ModuleBase
{

    function run(){

        $this->rightContent = $this->view->fetch ("invite.html");
    }


} 
