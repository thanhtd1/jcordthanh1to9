<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiSystem.php");
require_once(APD_DIR . "apdSystem.php");

$data = array();

$data["recid"] = "22";

$apd = new apdSystem();
$api = new apiSystem();

$apd->convertData($data);
$err = array();

$in = array();
$in[0] = "test";
$in[1] = $data;

$out = array();

$ret = $api->del($in, $out, $err);
print_r("ret = ".$ret."\n");
if ($ret <= 0) {
	var_dump($err);
}

var_dump($out);

?>
