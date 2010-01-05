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

                case "kayit":
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                    
                        if( isset($_POST['email'] ) ) {
                            $this->parent->view->assign("posted", true );

                            $registerErrors = $this->register($email, $password);

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
        }

    }


    function authenticate($email, $password) {

        $email = addslashes($email);
        $password = addslashes( $password );
        $password = $this->encryptPassword( $password );
       
        $sql = "select * from users where email='$email' and password='$password'";
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


    function register($email, $password) {
        $email = htmlspecialchars(addslashes($email));
        $password = addslashes ( $password );

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
            $this->parent->sendEmail("yuxel@sonsuzdongu.com","Kayit",$message); 
    }


    function sendLostPasswordMail($email, $hash){
            $this->parent->view->assign ( "hash", $hash );
            $this->parent->view->assign ( "email", $email );
            
            $message = $this->parent->view->fetch("../modules/uye/mails/lostpassword.html");    
            $this->parent->sendEmail("yuxel@sonsuzdongu.com","Parola hatirlatma",$message); 
    }

}


?>
