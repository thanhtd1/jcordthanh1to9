<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(API_DIR . "apiPerson.php");
require_once(APD_DIR . "apdPerson.php");

$api = new apiPerson();
$in = array();
$out = array();
$err = array();

$in[0] = 30;

$ret = $api->get($in, $out, $err);
print_r("ret = ".$ret."\n");

var_dump($out);
?>
