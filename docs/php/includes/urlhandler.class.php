<?

class urlHandler{
    public function parseUrlStrings(){
        $urlString = $_GET['q'];
        $urlStrings = explode("/", $urlString);
        
        foreach($urlStrings as $key=>$string){
            $newKey = "param_$key";
            $this->urlStrings->$newKey = $string;
        }
    }

    public function concatStrings(){
        $array = (array) $this->urlStrings;
        return implode("/",$array);
    }

    public function getDocumentRoot(){

        $host = trim($_SERVER['HTTP_HOST'], "/");

        $rootDir = trim( substr($_SERVER['PHP_SELF'], 0, -9), "/");

        if( $rootDir) {
            $rootDir = $rootDir."/";
        }
        $result =  "http://$host/$rootDir";

        $this->docRoot = $result;
    }


}
