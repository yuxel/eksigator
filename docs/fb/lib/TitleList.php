<?php

class TitleList extends ModuleBase
{

    function run(){
        $this->rightContent = $this->view->fetch ("list.html");
    }


} 
