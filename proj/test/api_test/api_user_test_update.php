<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiUser.php");
require_once(APD_DIR . "apdUser.php");

$data = array();
$data["recid"] = 11;
$data["bankid"] = 5;
$data["user_name"] = "9876543210";
$data["reg_stat"] = 0;
$data["passwd"] = "test99";
$data["org_name"] = "z";
$data["empname"] = "y";
$data["person"] = "x";
$data["furigana"] = "w";
$data["tel_num1"] = "v";
$data["tel_num2"] = "u";
$data["fax_num"] = "t";
$data["zip_code"] = "s";
$data["address1"] = "r";
$data["address2"] = "q";
$data["e_mail1"] = "p";
$data["e_mail2"] = "o";
$data["e_mail3"] = "n";
$data["e_mail4"] = "m";
$data["e_mail5"] = "l";
$data["kind"] = 5;
$data["note"] = "k";
$data["id_info"] = "j";
$data["country"] = "i";
$data["lock_flag"] = 0;
$data["lock_time"] = null;
$data["lock_cnt"] = 2;
$data["hosp_code"] = "j";
$data["pass_upd_date"] = null;

$in = array();
$in[0] = "test";
$in[1] = $data;

$out = array();
$err = array();

$api = new apiUser();
$ret = $api->upd($in, $out, $err);
print_r("ret = ".$ret."\n");
if ($ret <= 0) {
	var_dump($err);
}

?>
