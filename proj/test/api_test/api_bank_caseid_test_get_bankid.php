<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiBank_caseid.php");
require_once(APD_DIR . "apdBank_caseid.php");

$api = new apiBank_caseid();
$in = array();
$out = array();
$err = array();

$in[0] = 1;

$ret = $api->getBank_Id($in, $out, $err);
print_r("ret = ".$ret."\n");
if(empty($out)){
    var_dump($err);
    echo '<script>console.log('.json_encode($err).')</script>';
}
elseif(empty($err)){
    var_dump($out);
    echo '<script>console.log('.json_encode($out).')</script>';
}
?>
