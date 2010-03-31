<?
class module_uye implements modules{

    public function controller() {

        $action = $this->parent->url->urlStrings->param_1;


        switch($action) {
                case "giris":
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $authStatus = $this->authenticate($email, $password);
                   
                    if( !$authStatus ) {
                        return $this->parent->getModuleTemplate("loginFailed");
                    }
                    else{
                        $docRoot = $this->parent->url->docRoot;
                        header("Location: $docRoot");
                    }

                    break;

                case "sozluk":
                    $nick = addslashes($_GET['u']);
                    $ticket = addslashes($_GET['t']);
                
                    $action2 = $this->parent->url->urlStrings->param_2;

                    if ( $action2 == "getTicket" ) {
                        $this->generateTicketForSozluk($nick);
                    }
                    else {
                        return $this->authenticateViaSozluk($nick, $ticket);
                    }
                    break;

                case "kayit":
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $passwordAgain = $_POST['passwordAgain'];

                    
                        if( isset($_POST['email'] ) ) {
                            $this->parent->view->assign("posted", true );

                            $registerErrors = $this->register($email, $password, $passwordAgain);

                            $this->parent->view->assign("registerErrors", $registerErrors );
                        }

                        return $this->parent->getModuleTemplate("register");
                    break;

                case "cikis":
                        $this->logout();
                        $docRoot = $this->parent->url->docRoot;
                        header("Location: $docRoot");
                    break;


                case "parolamiUnuttum":
                        $email = $_POST['email'];
                    
                        if( isset($_POST['email'] ) ) {
                            $this->parent->view->assign("posted", true );
                            $lostPasswordErrors = $this->lostPassword($email);

                            $this->parent->view->assign("lostPasswordErrors", $lostPasswordErrors );
                        }

                        return $this->parent->getModuleTemplate("lostPassword");
                    break;

                case "aktiflestir":
                        $email = $this->parent->url->urlStrings->param_2;
                        $hash  = $this->parent->url->urlStrings->param_3;

                        $activateStatus = $this->activateUser ( $email, $hash );

                        $this->parent->view->assign ( "activateStatus", $activateStatus );

                        return $this->parent->getModuleTemplate("activate");
                    break;

                case "yeniParola":
                        $email = $this->parent->url->urlStrings->param_2;
                        $hash  = $this->parent->url->urlStrings->param_3;


                        $newPassword = $_POST['newPass'];
                        $newPasswordAgain = $_POST['newPassAgain'];

                        if( $newPassword  != $newPasswordAgain ) {
                            $passStatus = "notSame";
                        }
                        else {
                            $passStatus = $this->newPassword ( $email, $hash, $newPassword );
                        }

                        $this->parent->view->assign ( "passStatus", $passStatus );

                        return $this->parent->getModuleTemplate("newPass");
                    break;
        }

    }

    function newPassword($email=null, $hash=null, $newPassword=null) {
        $allow = false;

        if( $_SESSION['eksigator'] ) {
            $allow = true;
        }
        else{
            $email = addslashes($email);
            $hash  = addslashes($hash);

            $sql = "select id from users where email='$email' and hash='$hash'";
            $result = $this->parent->model->fetch($sql);

            $allow = empty($result) ? false : true;
        }

        if( !$allow ) {
            return "authError"; 
        }

        if( $allow && $newPassword ) {
            $encryptPassword = $this->encryptPassword($newPassword);
            $sql_update = "update users set password='$encryptPassword' where email='$email'";
            $this->parent->model->query($sql_update);
            return "changed";
        }

        return "allowed";

    }

    function activateUser($email, $hash) {
        $email = addslashes($email);
        $hash  = addslashes($hash);
        
        $sql = "select email, apiKey from users where email='$email' and hash='$hash'";
        $result = $this->parent->model->fetch($sql);
       
        $result = empty($result) ? false : $result[0];
        
        if($result ) {
            $newHash = $this->getNewHash();
            $sql_update = "update users set active = 1, hash='$newHash' where email='$email'";
            $this->parent->model->query($sql_update);
        }

        return $result;
    }

    function authenticate($email, $password) {

        $email = addslashes($email);
        $password = addslashes( $password );
        $password = $this->encryptPassword( $password );
       
        $sql = "select * from users where email='$email' and password='$password' and active=1";
        $result = $this->parent->model->fetch($sql);

        if(!$result) {
            return false;
        }
        else{
            $result = $result[0];

            $_SESSION['eksigator'] = $result;

            return $result;
        }

    } 

    function logout(){
        unset( $_SESSION['eksigator'] );
    }

    function encryptPassword ($password) {
        $md5 = md5($password);
        return substr( $md5, 2, 40);
    }


    function register($email, $password, $passwordAgain) {
        $email = htmlspecialchars(addslashes($email));
        $password = addslashes ( $password );
        $passwordAgain = addslashes ( $passwordAgain );

        $errors = array();

        if( !$this->isValidEmail($email) ) {
            $errors['email'] = 'notValid';
        }
        elseif( $this->emailExists($email) ) {
            $errors['email'] = 'exists';
        }
           

        if ( !$this->passwordValid ( $password ) ) {
            $errors['password'] = 'short';
        }
        elseif ( $password != $passwordAgain ) {
            $errors['password'] = "notSame";
        }


        if( empty ( $errors ) ) {
            $this->registerUser( $email, $password );
            return null;
        }
        else{
            return $errors;
        }

    }


    function isValidEmail($email) {
         if (!eregi ( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email ))
         
             return false;
         else
         return true;
     }



    function emailExists($email) {
        $sql = "select email from users where email='$email'";
        $result = $this->parent->model->fetch($sql);

        return $result;
    }

    function passwordValid($password) {

        if( strlen($password) < 6)  {
            return false;
        }

        return true;
    }

    
    function lostPassword($email) {
        $email = htmlspecialchars ( addslashes ( $email ) );

        if( !$this->isValidEmail($email) ) {
            $errors['email'] = 'notValid';
        }
        elseif( !$this->emailExists($email) ) {
            $errors['email'] = 'notExists';
        }
    
        if( empty ( $errors ) ) {
            $newHash = $this->getNewHash();
            $sql = "update users set hash='$newHash' where email='$email'";
            $this->parent->model->query($sql);
            $this->sendLostPasswordMail($email, $newHash);
            return null;
        }
        else{
            return $errors;
        }
    }


    function getNewHash() {
        return substr( uniqid(), 0 ,20 );
    }

    function registerUser($email, $password) {
        $hash = $this->getNewHash();
        $apiKey = md5($hash);
        $password = $this->encryptPassword ( $password);

        $sql = "insert into users (email, password, apiKey, auth, hash, active) 
            values ( '$email', '$password', '$apiKey',0, '$hash',0) ";

        $this->parent->model->query ( $sql );

        $this->sendRegistrationEmail ( $email , $hash );
    }

    function sendRegistrationEmail($email, $hash){
            $this->parent->view->assign ( "hash", $hash );
            $this->parent->view->assign ( "email", $email );
            
            $message = $this->parent->view->fetch("../modules/uye/mails/register.html");    
            $this->parent->sendEmail($email,"Kayit",$message); 
    }


    function sendLostPasswordMail($email, $hash){
            $this->parent->view->assign ( "hash", $hash );
            $this->parent->view->assign ( "email", $email );
            
            $message = $this->parent->view->fetch("../modules/uye/mails/lostpassword.html");    
            $this->parent->sendEmail($email,"Parola hatirlatma",$message); 
    }







    function checkForValidIp(){

        $ip ="188.132.200."; 
        $remote = $_SERVER['REMOTE_ADDR'];

        if( preg_match("/^$ip/",$remote) ) {
            return true;
        }

        return false;

    }





    function generateTicketForSozluk($nick) {

        if( !checkForValidIp() ) {
            return false;
        }
        //@todo ip control
        require_once 'conf/eksisozluk.php';
        $now = time();
        $text = $nick.":::".$now;
        $secret = $eksisozlukConf['secret']; 

        $encrypted = Encryption::encrypt($text,$secret);
        $encrypted = urlencode($encrypted);

        echo $encrypted;

        exit;
    }






    function authenticateViaSozluk($nick, $ticket) {

        if( !checkForValidIp() ) {
            return $this->parent->getModuleTemplate("loginFailed");
        }

        //@todo ip kontrol
        require_once 'conf/eksisozluk.php';
        $secret = $eksisozlukConf['secret'];

        $decrypted = Encryption::decrypt($ticket, $secret);

        list($nick, $time) = explode(":::", $decrypted);

        $now = time();

        $ticketDuration = abs($now - $time);

        if( $ticketDuration < 360 ) {

            $sql = "select e.eksigator_id, e.suser_nick, u.auth, u.email, u.apiKey from eksisozluk as e, users as u where
                    e.suser_nick='$nick' and e.eksigator_id = u.id and u.active=1";

            $result = $this->parent->model->fetch($sql);

            if(!$result) {
                    
                $email = "$nick@eksisozluk";
                $hash = $this->getNewHash();
                $apiKey = md5($hash);

                $userSql = "insert into users (email, apiKey, auth, active, hash)
                            values ('$email', '$apiKey', 1,1, '$hash')";
            
                $this->parent->model->query($userSql);
                $lastIdQ = "select id from users  where email='$email'";

                $result = $this->parent->model->fetch($lastIdQ);

                $lastId = $result[0]['id'];

                $eksiSql = "insert into eksisozluk (suser_nick, eksigator_id) 
                                values ('$nick', '$lastId')";


                $this->parent->model->query($eksiSql);
                    
                $sql = "select e.eksigator_id, e.suser_nick, u.auth, u.email, u.apiKey from eksisozluk as e, users as u where
                    e.suser_nick='$nick' and e.eksigator_id = u.id and u.active=1";
                $result = $this->parent->model->fetch($sql);


            }

            $_SESSION['eksigator'] = $result[0];


            $redirect = $this->parent->url->docRoot. "goster";

            header("Location: $redirect");

            exit;
        }
        else {
            return $this->parent->getModuleTemplate("loginFailed");
        }

    }


}

