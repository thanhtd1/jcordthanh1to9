<?php

$wrkdir = dirname(__FILE__);
require($wrkdir . '/constants.php');
require($wrkdir . '/functions.php');

//引数チェック
$srcfl = $argv[1];
if (!$srcfl) exitWithError("\nerror: the input file was not specified! \n");
if (!file_exists($srcfl)) exitWithError("\nerror: the specified file (" . $srcfl . ") does not exist! \n");

//ログイン処理
//$cookiepath = login();
//if (!$cookiepath) exitWithError("\nerror: login failed.") does not exist! \n");

//JSONデータを取り込んで送信する
$rowcnt = 0;
$srcfp = fopen($srcfl, "r");
while (($itms = fgetcsv($srcfp))) {
    $rowcnt++;
    $webapi = $itms[0];
    $srcjsn = $itms[1];
    $url = 'http://' . WEB_SERVER . $webapi;
	$outjsn = sendJson($url, $srcjsn, null);
	//$outjsn = sendJson($url, $srcjsn, $cookiepath);
	//fputcsv(STDOUT, array($webapi, trim($outjsn)));
	fputcsv(STDOUT, array($webapi, json_encode(json_decode($outjsn, true), JSON_UNESCAPED_UNICODE)));
}
fclose($srcfp);

//Cookieファイルの削除
//unlink($cookiepath);

