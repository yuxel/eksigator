<?php

class ModuleBase
{

    function setFacebookClient($parent) {
        $this->view = new Smarty();
        $this->facebook = $parent;

        $this->run();
    }


}
