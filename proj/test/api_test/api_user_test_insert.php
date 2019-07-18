<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiUser.php");
require_once(APD_DIR . "apdUser.php");

$data = array();
$data["bankid"] = 3;
$data["user_name"] = "1234567893";
$data["reg_stat"] = 0;
$data["passwd"] = "test03";
$data["org_name"] = "aaaa";
$data["empname"] = "bbbb";
$data["person"] = "cccv";
$data["furigana"] = "dddd";
$data["tel_num1"] = "eeee";
$data["tel_num2"] = "ffff";
$data["fax_num"] = "gggg";
$data["zip_code"] = "hhhh";
$data["address1"] = "iiii";
$data["address2"] = "jjjj";
$data["e_mail1"] = "kkkk";
$data["e_mail2"] = "llll";
$data["e_mail3"] = "mmmm";
$data["e_mail4"] = "nnnn";
$data["e_mail5"] = "oooo";
$data["kind"] = 5;
$data["note"] = "pppp";
$data["id_info"] = "qqqq";
$data["country"] = "rrrr";
$data["lock_flag"] = 0;
$data["lock_time"] = null;
$data["lock_cnt"] = 1;
$data["hosp_code"] = "ssss";
$data["pass_upd_date"] = null;

$apd = new apdUser();
$out_apd = new apdUser();
$api = new apiUser();

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
