<?
require_once 'conf/db.php';
require_once 'conf/mail.php';
require_once 'includes/moduleInterface.php'; //modules interface
require_once 'includes/db.class.php';
require_once '3rdParty/smarty/libs/Smarty.class.php';
require_once 'includes/eksigator.class.php';
require_once 'includes/urlhandler.class.php';
require_once 'includes/encryption.class.php';

session_start();

$eksigator = new Eksigator();

$eksigator->initUrlHandler();
$eksigator->url->parseUrlStrings();
$eksigator->url->getDocumentRoot();
$eksigator->initSiteLanguage(); //kullanicinin diline uygun olarak dili ayarlar

if($_GET['changeLanguage']) {
    $eksigator->changeLanguage($_GET['changeLanguage']);
}

$eksigator->initView();
$eksigator->initModel();


$currentModule = $eksigator->getCurrentModule();


$eksigator->loadModule($currentModule);

