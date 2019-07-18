<?php
session_start();
require_once("../.config.php");
require_once(COMM_DIR . "define.php");
require_once(LOGIC_DIR . "logicPerson.php");
require_once(APD_DIR . "apdPerson.php");

$data = array();
$data["recid"] = "11";
$data["name"] = "テスト4";
$data["mail"] = "test4@nisp.jp";
$data["company_name"] = "株式会社テスト4";
$data["division"] = "テスト部";
$data["start_date"] = "2019/02/01";
$data["del_flag"] = 0;

$logic = new logicPerson();
$apd = new apdPerson();
$err = array();

$apd->convertUpdateData($data);

$ret = $logic->modPerson($apd);
print_r("ret = ".$ret."\n");


?>
