<?php

class UserSettings extends ModuleBase
{

    function run(){

        $email = addslashes(htmlspecialchars($_POST['eksigator'][0]));
        $apiKey = addslashes(htmlspecialchars($_POST['eksigator'][1]));

        $interval = (int) $_POST['interval'];

        if( $interval == 0 || $interval == 1 || $interval == 3 || 
            $interval == 6 || $interval == 12 || $interval == 24 ) {
            $interval = $interval;
        }
        else {
            $interval = 0;
        }

        $this->updateUserData ( $email, $apiKey, $interval );

        $this->view->assign("userData", $this->parent->userData);
        $this->rightContent = $this->view->fetch("settings.html");
    }


    function updateUserData($email, $apiKey, $interval) {

        try {
            $this->parent->getData($email, $apiKey);

            $idQuery = "select id from users 
                where 
                email='$email' 
                and apiKey='$apiKey'";

            $id = $this->parent->db->fetch($idQuery);

            $eksigatorId = $id[0]['id'];

            $fbId = $this->parent->facebookUser;

            $facebookQuery = "insert into facebook (fb_id, eksigator_id, `interval`)
                        values ('$fbId','$eksigatorId', '$interval')
                        on duplicate key 
                        update eksigator_id = values (eksigator_id), `interval` = values (`interval`)";

            echo "<br/>".$facebookQuery."<br/>";

            $this->parent->db->query($facebookQuery);

            $this->view->assign("success",true);
        }
        catch( Exception $e) {
            $this->view->assign("success",true);
        }

    }

} 
