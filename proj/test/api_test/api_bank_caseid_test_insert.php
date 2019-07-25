<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiBank_caseid.php");
require_once(APD_DIR . "apdBank_caseid.php");

$data = array();
// $data["bankid"] = "2";
$data["bankid"] = "3";
$data["seq_no"] = "2";

$apd = new apdBank_caseid();
$out_apd = new apdBank_caseid();
$api = new apiBank_caseid();

$apd->convertData($data);
$err = array();

$in = array();
$in[0] = "test";
$in[1] = $data;

$out = array();

$ret = $api->add($in, $out, $err);
print_r("ret = ".$ret."\n");
if ($ret <= 0) {
	var_dump($err);
}

var_dump($out);

?>
