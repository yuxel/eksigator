<?php

class FetchNews extends ModuleBase
{

    function run(){

        var_dump ( $this->parent->userData );

        $this->rightContent = $this->view->fetch("news.html");
    }




} 
