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

        $query = "select * from users limit 1";
        $data =$this->db->fetch($query);

        var_dump ( $data );

        $this->rightContent = $this->view->fetch("notSigned.html");
    }

}
