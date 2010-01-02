<?
class Auth {

    function __construct($model) {
        $this->model = $model;
    }

    function authenticateUser($email, $password) {

        $email = addslashes ( $email );
        $password  = addslashes ( $password );

        $password = $this->encryptPassword ( $password );

        $fetched = $this->model->fetch( "select id from users where email='$email' and password='$password'");

        var_dump ( $fetched );



    }


    function encryptPassword ($password) {
        $md5 = md5($password);
        return substr( $md5, 0, 40);
    }

}
