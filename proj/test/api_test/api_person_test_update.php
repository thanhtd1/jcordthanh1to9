<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(API_DIR . "apiPerson.php");
require_once(APD_DIR . "apdPerson.php");

$data = array();
$data["recid"] = "";
$data["name"] = "テスト11";
$data["mail"] = "test11@nisp.jp";
$data["company_name"] = "株式会社テスト11";
$data["division"] = "テスト11部";
$data["start_date"] = "2019/06/01";
$data["del_flag"] = 0;

$in = array();
$in[0] = "test";
$in[1] = $data;

$out = array();
$err = array();

$api = new apiPerson();
$ret = $api->upd($in, $out, $err);
print_r("ret = ".$ret."\n");
if ($ret <= 0) {
	var_dump($err);
}

?>
