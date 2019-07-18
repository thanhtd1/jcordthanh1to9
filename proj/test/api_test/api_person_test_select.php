<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(API_DIR . "apiPerson.php");
require_once(APD_DIR . "apdPerson.php");

$api = new apiPerson();
$apd = new apdPerson();
$err = array();

$ret = $api->select($apd,$err);
print_r("ret = ".$ret."\n");

var_dump($apd->getData());
?>
