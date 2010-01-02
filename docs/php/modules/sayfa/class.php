<?
class module_sayfa implements modules{

    public function controller() {

        $this->parent->loadModuleLanguage();
        $page =  $this->parent->url->urlStrings;

        $file = $page->param_1;
        $mainContent = $this->getPageTemplate($file);

        return $mainContent;
    }

    public function getPageTemplate($file) {

        $template = "../modules/sayfa/page_templates/$file.html";

        if(!$this->parent->view->template_exists($template)) {
            return $this->parent->getLanguageValue("view","viewNotExists");
        }
        else {
            return $this->parent->view->fetch($template);
        }
    }


}


?>
