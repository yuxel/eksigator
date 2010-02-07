<?
class module_main_page implements modules{

    public function controller() {

        $this->parent->loadModuleLanguage();
        $urls = $this->parent->url->urlStrings;
        $action = $urls->param_2;

        $clouds = $this->getTagCloud();


        $this->parent->view->assign("clouds", $clouds);
        
        $output = $this->printMainPage();

        return $output;

    }

    public function printMainPage(){
        return $this->parent->getModuleTemplate("main_page");
    }



    public function getFlvs() {
        include_once("modules/videos/class.php");
        return module_videos::getFlvList();
    }

    public function getSlides(){
       $slides = $this->listImages("Slides/".$this->parent->siteLanguage);
       shuffle($slides);
       return $slides; 
    }


    public function listImages($dir){
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(preg_match("/jpg$/",$file)){
                        $files[] = $file;
                    }
                }
            }
            closedir($handle);
        }
        return $files;
    }


    public function getTagCloud(){

        $sql = "SELECT title, count(userId) as count FROM `entries` where deleted=0 group by title order by count desc limit 20";
        $tags = $this->parent->model->fetch ( $sql );
        $first = current($tags);
        $last = end ( $tags );
        
        
        $title_length = 0;
        foreach ( $tags as $tag) {
            $title_length = $title_length + strlen( $tag['title'] );
        }


        $maxEm = 400 / $title_length;

        if($maxEm > 5) {
            $maxEm = 5;
        }
        if($maxEm < 3) {
            $maxEm = 3;
        }

        $maxCount = $first['count'];
        $minCount = $last['count'];

        $tagCount = count ( $tags ); 

        $ratio = $maxCount / $minCount;
        $emRatio = $maxEm / $ratio;
        $diffWithOne = 1 - $emRatio;


        $cloud =  array();
        foreach ( $tags as $key=>$tag) {
                $ratio = $diffWithOne + ( $tag['count'] * $emRatio );
                $ratio = number_format($ratio, 2, '.', '');
                
                $fontWeight = ( ($key % 2) ==0 ) ? "bold" : "normal";

                $cloud[] = array("title"=> $tag['title'],
                                 "ratio"=> $ratio,
                                 "fontWeight" => $fontWeight,
                                 "count"=> $tag['count'] );
        }



        shuffle($cloud);

        $firstPart = ceil($tagCount * 0.7);
        $secondPart = $tagCount - $firstPart;

        $firstCloud = array_slice( $cloud, 0 , $firstPart );
        $secondCloud = array_slice ( $cloud, $firstPart, $secondPart );


        return array("first"  => $firstCloud, 
                     "second" => $secondCloud);

    }
}


?>
