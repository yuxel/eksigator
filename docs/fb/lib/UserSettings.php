<?php

class UserSettings extends ModuleBase
{

    function run(){
       $this->rightContent = $this->view->fetch("settings.html");
    }

} 
