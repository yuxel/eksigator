<?php
/**
 * i10n for eksigator
 *
 * @author   Osman Yuksel
 * @param string
 */
function smarty_modifier_lang($string)
{
    global $eksigator;
    list($nameSpace, $value) = explode("/", $string);
     $languageValue = $eksigator->getLanguageValue($nameSpace, $value);

     return $languageValue;
}


?>
