<?
class Eksigator {

    public function initView(){

        $this->siteLanguage = "tr";
        include_once("lang/$this->siteLanguage.php");
        $this->view = new Smarty;
        $this->view->plugins_dir[] = "viewPlugins";

        //$this->view->compile_id = implode("_",(array)$this->url->urlStrings);
        //$this->view->cache_id = implode("_",(array)$this->url->urlStrings);

        //$this->view->force_compile = true;
        //$this->view->compile_check = true;
        //$smarty->debugging = true;
        
        $this->view->assign("URL", $this->url->docRoot);
        $this->view->assign("URLStrings", $this->url->concatStrings() );
        $this->view->assign("lang", $this->siteLanguage);

    }


    public function getLanguageValue($nameSpace, $value) {
        $languageValue = $GLOBALS['_l'][$nameSpace][$value];
        $languageValue = $languageValue?$languageValue:"[$value]";
        return $languageValue;
    }

    public function initModel() {
        $dbConf = $GLOBALS['dbConf'];
        $this->model = new model($dbConf);
    }


    public function initUrlHandler(){
        $this->url = new urlHandler();
    }


    public function initSiteLanguage(){
        $this->siteLanguage = "tr";
    }

    public function getSiteLanguage(){
        $this->siteLanguage = $this->url->urlStrings->param_0;
    }

    public function changeLanguage($newLang) {
        $validLanguages = array("tr","en");

        $newUrl = $this->url->docRoot.$this->url->concatStrings();
        if( in_array($newLang, $validLanguages) && $newLang != $this->siteLanguage) {
            $this->url->urlStrings->param_0 = $newLang;
            $newUrl = $this->url->docRoot.$this->url->concatStrings();
        }

        header("Location: $newUrl");
    }



    public function runModuleAndDisplayPage() {

        $mainTemplate = "mainContent.html";

        //$this->view->force_compile = true;
        //$this->view->compile_check = true;


        $moduleContent = $this->runModuleController();

        $userLoggedIn = $this->userLoggedIn();

        //var_dump ( $moduleContent );

        $this->view->assign("moduleContent", $moduleContent );
        $this->view->assign("userLoggedIn", $userLoggedIn );

        $this->view->display($mainTemplate);

    }



    public function getCurrentModule() {
        $currentModule = $this->url->urlStrings->param_0;
        if(!$currentModule) {
            $currentModule = "main_page";
        }
        return $currentModule;

    }

    public function loadModule($module) {

        $this->view->assign("moduleName", $module);
        $moduleClassFile = "modules/$module/class.php";
        if( !file_exists ( $moduleClassFile ) ) {
            $this->view->assign("noSuchModule", "true");
            return false;
        }

        include_once($moduleClassFile);
        $this->moduleName = $module;
        $moduleObject = "module_$module";
        $this->module = new $moduleObject;
        $this->module->parent = $this;
    }

    public function loadModuleLanguage() {
        include_once("modules/$this->moduleName/lang/$this->siteLanguage.php");
    }
    public function runModuleController() {
        if( $this->module ) {
            return $this->module->controller();
        }
    }

    public function getModuleTemplate($template, $module=null) {
        if(!$module) {
            $module = $this->moduleName;
        }
        return $this->view->fetch("../modules/$module/templates/$template.html");    
    }


    public function userLoggedIn(){
       
        if(!$_SESSION['eksigator']) {
            return false;
        }
        return $_SESSION;
    }

}
