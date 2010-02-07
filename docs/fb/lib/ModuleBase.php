<?php

class ModuleBase
{

    function setFacebookClient($parent) {
        $this->facebook = $parent;
    }

    function setAction($action) {
        $this->action = $action;
    }

    function initView () {
        $this->view = new Smarty();
        $this->view->assign("action", $this->action );
        $this->view->assign("URL", "http://apps.facebook.com/eksigator");
    }

    function printPage() {
        $this->view->assign("leftContent", $this->leftContent);
        $this->view->assign("rightContent", $this->rightContent);
        $this->view->display("main.html");
    }

}
