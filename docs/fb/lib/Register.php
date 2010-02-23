<?php

class Register extends ModuleBase
{
    function run() {
        $email = $_POST['eksigator'][0];
        $apiKey = $_POST['eksigator'][1];


        if($email && $apiKey) {
            try {
                $this->parent->getData($email, $apiKey);

                $query = "select id from users 
                          where 
                          email='$email' 
                          and apiKey='$apiKey'";

                $id = $this->parent->db->fetch($query);

                var_dump ( $id );
                

                $this->view->assign("success",true);
            }
            catch(Exception $e) {
                $this->view->assign("success",false);
            }
        }


        $this->rightContent = $this->view->fetch("notSigned.html");
    }

}
