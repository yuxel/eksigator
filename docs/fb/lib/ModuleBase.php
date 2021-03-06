<?php

class ModuleBase
{

    function setParent($parent) {
        $this->parent = $parent;
    }

    function setFacebookClient($parent) {
        $this->facebook = $parent;
    }

    function setAction($action) {
        $this->action = $action;
    }


    function initView () {
        $this->view = new Smarty();
        $this->view->assign("action", $this->action );
        $fbUrl = $this->parent->fbUrl;
        $fbUrl = trim($fbUrl,"/");
        $this->view->assign("URL", $fbUrl);
        $this->view->assign("fbApiUrl", $this->parent->fbApiUrl);
        $this->view->assign("loggedIn", $this->parent->userLoggedIn);
    }

    function printPage() {

        $main = "main.html";
        if ( $this->name == "InviteFriends" ) {
            $main= "invite_main.html";
        } 


        $this->view->assign("leftContent", $this->leftContent);
        $this->view->assign("rightContent", $this->rightContent);
        $this->view->display($main);



    }

}
