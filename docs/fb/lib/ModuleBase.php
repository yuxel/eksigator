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
    }

    function printPage() {
        $this->view->assign("leftContent", $this->leftContent);
        $this->view->assign("rightContent", $this->rightContent);
        $this->view->display("main.html");
    }

}
