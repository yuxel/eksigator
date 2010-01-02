<?
class module_iletisim implements modules{

    public function controller() {
        $this->parent->loadModuleLanguage();
        $this->listStaff();


        if($_POST['message']) {
            $this->sendMail( $_POST['nameSurname'], $_POST['eMail'], $_POST['message']);
            $this->parent->view->assign("mailSent",true);

        }

        return $this->parent->getModuleTemplate("contact");

    }


    public function sendMail($nameSurname, $email, $message) {
        $nameSurname = htmlspecialchars ( addslashes( $nameSurname ) );
        $email = htmlspecialchars ( addslashes( $email ) );
        $message = htmlspecialchars ( addslashes( $message ) );

        return true;
    }


    function listStaff() {
        
        $staff = array( array("title"=>"contact/yonetimKuruluBaskani",
                              "name"=>"Fatih USTA",
                              "tel"=>"(+90) 216 486 22 33 - 128",
                              "email"=>"fatihusta@mastermovie.com.tr"),
                        
                         array("title"=>"contact/genelKoordinator",
                              "name"=>"Emre GÜNAY",
                              "tel"=>"(+90) 216 486 22 33 - 120",
                              "email"=>"emregunay@mastermovie.com.tr"),

                         array("title"=>"contact/teknikDanisman",
                               "sub_title"=>"contact/teknikDanismanSub",
                              "name"=>"Erkan UMUT",
                              "tel"=>"(+90) 216 486 22 33 - 121",
                              "mobile"=>"(+90) 533 433 8 433",
                              "email"=>"erkanumut@mastermovie.com.tr"),


                         array("title"=>"contact/ceo",
                              "name"=>"Cihan KARADEMİR",
                              "tel"=>"(+90) 216 486 22 33 - 122",
                              "email"=>"cihankarademir@mastermovie.com.tr"),

                         array("title"=>"contact/artDirector",
                              "name"=>"Ümit GÜLMEZ",
                              "tel"=>"(+90) 216 486 22 33 - 114",
                              "email"=>"umitgulmez@mastermovie.com.tr"),

                         array("title"=>"contact/vfx",
                              "name"=>"Abdurrahman SEÇER",
                              "tel"=>"(+90) 216 486 22 33 - 115",
                              "email"=>"abdsecer@mastermovie.com.tr"),

                         array("title"=>"contact/muhasebe",
                              "name"=>"Yalçın KARADAŞ",
                              "tel"=>"(+90) 216 486 22 33 - 119",
                              "email"=>"yalcinkaradas@mastermovie.com.tr"),

                         array("title"=>"contact/koordinator",
                              "name"=>"Bilal İNCE",
                              "tel"=>"(+90) 216 486 22 33 - 118",
                              "email"=>"bilalince@mastermovie.com.tr"),

                         array("title"=>"contact/musteriIliskileri",
                              "name"=>"Rabia YÜCEYURT",
                              "tel"=>"(+90) 216 486 22 33 - 111",
                              "email"=>"rabiayuceyurt@mastermovie.com.tr")
                            
                    );


            $this->parent->view->assign("staff", $staff);
    }


}


?>
