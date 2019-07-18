<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiUser.php");
require_once(APD_DIR . "apdUser.php");

$api = new apiUser();
$in = array();
$out = array();
$err = array();

$in[0] = 11;

$ret = $api->get($in, $out, $err);
print_r("ret = ".$ret."\n");

var_dump($out);
?>
