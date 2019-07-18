<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(API_DIR . "apiPerson.php");
require_once(APD_DIR . "apdPerson.php");

$data = array();
$data["name"] = "テスト太郎12";
$data["mail"] = "";
$data["company_name"] = "株式会社テスト12";
$data["division"] = "abc部";
$data["start_date"] = "2019/06/06";

$apd = new apdPerson();
$out_apd = new apdPerson();
$api = new apiPerson();

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
