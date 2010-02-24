<?php

class Help extends ModuleBase
{

    function run(){
        $this->rightContent = $this->view->fetch ("help.html");
    }


} 
