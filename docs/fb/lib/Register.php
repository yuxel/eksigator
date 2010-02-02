<?php

class Register extends ModuleBase
{
    function run() {
        $this->view->display("notSigned.html");
    }
}
