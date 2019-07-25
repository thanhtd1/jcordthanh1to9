<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(WEBAPI_DIR . "apiState.php");
require_once(APD_DIR . "apdState.php");

$api = new apiState();
$in = array();
$out = array();
$err = array();

$in[0] = 1;

$ret = $api->get($in, $out, $err);
print_r("ret = ".$ret."\n");

var_dump($out);
echo '<script>console.log('.json_encode($out).')</script>';
?>
