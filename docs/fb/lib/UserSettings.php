<?php

class UserSettings extends ModuleBase
{

    function run(){

        $this->view->assign("userData", $this->parent->userData);

        $this->rightContent = $this->view->fetch("settings.html");
    }

} 
