<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiUser.php");
require_once(APD_DIR . "apdUser.php");

$api = new apiUser();
$apd = new apdUser();
$err = array();

$in = array();
$out = array();

$in[0] = null;
$in[1] = null;
$in[2] = null;

$ret = $api->list($in,$out,$err);
print_r("ret = ".$ret."\n");

var_dump($out);
?>
