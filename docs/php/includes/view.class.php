<?
require_once '3rdParty/smarty/libs/Smarty.class.php';

class view extends Smarty {

    function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false)
    {
        
        return parent::fetch($resource_name, $cache_id = null, $compile_id = null, $display = false);
    }   


}
