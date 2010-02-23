<?php

class Register extends ModuleBase
{
    function run() {
        $email = $_POST['eksigator'][0];
        $apiKey = $_POST['eksigator'][1];


        if($email && $apiKey) {
            try {
                $this->parent->getData($email, $apiKey);

                $idQuery = "select id from users 
                          where 
                          email='$email' 
                          and apiKey='$apiKey'";

                $id = $this->parent->db->fetch($idQuery);

                $eksigatorId = $id[0]['id'];
                
                $fbId = $this->parent->facebookUser;

                $facebookQuery = "insert into facebook (fb_id, eksigator_id)
                                  values ('$fbId','$eksigatorId')
                                  on duplicate key 
                                  update eksigator_id = values (eksigator_id)";

                $this->parent->db->query($facebookQuery);

                $this->facebook->redirect("http://apps.facebook.com/eksigator");

                $this->view->assign("success",true);
            }
            catch(Exception $e) {
                $this->view->assign("success",false);
            }
        }


        $this->rightContent = $this->view->fetch("notSigned.html");
    }



}
