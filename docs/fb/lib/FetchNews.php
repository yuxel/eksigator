<?php

class FetchNews extends ModuleBase
{

    function run(){
        $this->rightContent = $this->view->fetch("news.html");
    }




} 
