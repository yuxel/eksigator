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
                        return $this->parent->getModuleTemplate("register");
                    break;

                case "cikis":
                        $this->logout();
                        $docRoot = $this->parent->url->docRoot;
                        header("Location: $docRoot");
                    break;


                case "parolamiUnuttum":
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

}


?>
