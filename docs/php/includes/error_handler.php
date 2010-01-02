<?
function eksigatorErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
      case E_USER_ERROR:
          //echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
          //echo "  Fatal error on line $errline in file $errfile";
          //echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
          //echo "Aborting...<br />\n";
          //exit(1);
          break;

      case E_USER_WARNING:
          if( preg_match("/Smarty error: unable to read resource/", $errstr) ) {
              global $eksigator;
              $eksigator->view->assign("cannotLoadView", "foo");
          } 


          break;

      case E_USER_NOTICE:
          //echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
          break;

      default:
          //echo "Unknown error type: [$errno] $errstr<br />\n";
          break;
    }

    return true;{

    }

}
