<?php

class InviteFriends extends ModuleBase
{

    function run(){

        var_dump ( $this->name );

        $this->rightContent = $this->view->fetch ("invite.html");
    }


} 
