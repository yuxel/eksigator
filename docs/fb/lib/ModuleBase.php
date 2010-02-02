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
    }

}
