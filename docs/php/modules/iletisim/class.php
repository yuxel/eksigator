<?
class module_iletisim implements modules{

    public function controller() {
        $this->parent->loadModuleLanguage();


        if($_POST['message']) {
            $this->sendMail( $_POST['nameSurname'], $_POST['eMail'], $_POST['message']);
            $this->parent->view->assign("mailSent",true);

        }

        return $this->parent->getModuleTemplate("contact");

    }


    public function sendMail($nameSurname, $email, $message) {
        $this->parent->view->assign("senderNameSurname",  htmlspecialchars ( addslashes( $nameSurname ) ) );
        $this->parent->view->assign("senderEmail", htmlspecialchars ( addslashes( $email ) ));
        $this->parent->view->assign("senderMessage", htmlspecialchars ( addslashes( $message ) ));

        
        $template = $this->parent->view->fetch("../modules/iletisim/mails/contact.html");    
        $this->parent->sendEmail("eksigator@eksigator.com","Iletisim",$template); 

        return true;
    }


}


?>
