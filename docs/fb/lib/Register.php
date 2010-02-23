<?php

class Register extends ModuleBase
{
    function run() {
        $email = $_POST['eksigator'][0];
        $apiKey = $_POST['eksigator'][1];


        if($email && $apiKey) {
            try {
                $this->parent->getData($email, $apiKey);
                $this->view->assign("success",true);
            }
            catch(Exception $e) {
                $this->view->assign("success",false);
            }
        }


        $this->rightContent = $this->view->fetch("notSigned.html");
    }

}
