<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiSystem.php");
require_once(APD_DIR . "apdSystem.php");

$api = new apiSystem();
$in = array();
$out = array();
$err = array();

//$in[0] = 0;
$in[0] = 1;

$ret = $api->get($in, $out, $err);
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
