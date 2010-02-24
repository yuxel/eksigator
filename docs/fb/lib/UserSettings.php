<?php

class UserSettings extends ModuleBase
{

    function run(){

        var_dump ( $this->parent->userData );
        $this->rightContent = $this->view->fetch("settings.html");
    }

} 
