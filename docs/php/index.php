<?php
require_once 'bootstrap.php'; //init main
global $eksigator; //global object


error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

$eksigator->runModuleAndDisplayPage();

