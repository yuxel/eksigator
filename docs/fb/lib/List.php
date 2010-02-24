<?php

class List extends ModuleBase
{

    function run(){
        $this->rightContent = $this->view->fetch ("list.html");
    }


} 
