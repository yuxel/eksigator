<?
class module_goster implements modules{

    public function controller() {
        return $this->parent->getModuleTemplate("show");
    }

}
