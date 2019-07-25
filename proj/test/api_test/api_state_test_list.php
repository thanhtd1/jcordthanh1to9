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
$int = array();
$where = new stdClass;
$where->isConverted = true;
$order = new stdClass;
$order->lines = 5;
$order->page = 0;
$order->sortDir = 1;
$order->sortKey=['recid'];
$in[] = "query";
$in[] = $where;
$in[] = $order;
$in[] = 1;

$ret = $api->list($in, $out, $err);
print_r("ret = ".$ret."\n");

var_dump($out);
echo '<script>console.log('.json_encode($out).')</script>';
?>
