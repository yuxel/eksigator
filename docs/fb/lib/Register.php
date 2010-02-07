<?php

class Register extends ModuleBase
{
    function run() {
        $this->rightContent = $this->view->fetch("notSigned.html");
    }
}
