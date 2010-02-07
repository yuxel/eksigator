<?php

class Register extends ModuleBase
{
    function run() {
        $this->rigthContent = $this->view->fetch("notSigned.html");
    }
}
