<?
class module_goster implements modules{

    public function controller() {

        $this->userEmail = $_SESSION['eksigator']['email'];
        $this->userEmail = urlencode($this->userEmail);
        $this->userApiKey = $_SESSION['eksigator']['apiKey'];
        $this->apiUrl = "http://api.eksigator.com/";
        $this->requestUrl = $this->apiUrl.$this->userEmail."/".$this->userApiKey."/";


        if($_SESSION['eksigator']) {

            $isAjax = $_GET['ajax'];


            $page =  $this->parent->url->urlStrings;
            
            $action = $page->param_1;
            $title  = $_POST['title'];
            $title  = urlencode($title);

            switch($action) {
                case "addToList":
                    $data = $this->addToUsersList($title);
                    break;

                case "removeFromList":
                    $data = $this->removeFromUsersList($title);
                    break;

                case "setItemAsRead":
                    $data = $this->setItemAsRead($title);
                    break;

               case "getList":
               default:
                    $data = $this->getUsersList();
                    break;
            }

            if($isAjax) {
                echo $data;
                exit;
            }
            else{
                $data = json_decode($data);
                $this->parent->view->assign("usersList", $data);
            }

            
            return $this->parent->getModuleTemplate("show");
        }
    }


    public function getUsersList() {
        $command = $this->requestUrl. "getList";
        $json = file_get_contents($command);

        $array = json_decode($json);

        foreach($array as $item) {
            $newArray[$item->status] [] = $item;
        }

        $json = json_encode($newArray);

        return $json;
    }

    public function addToUsersList($title) {
        var_dump($title);
        $command = $this->requestUrl . "addToList/".$title;
        $json = file_get_contents($command);

        return $json;
    }

    public function removeFromUsersList($title) {
        $command = $this->requestUrl . "removeFromList/".$title;
        $json = file_get_contents($command);

        return $json;
    }

    public function setItemAsRead($title) {
        $command = $this->requestUrl . "setItemAsRead/".$title;
        $json = file_get_contents($command);

        return $json;

    }



}
