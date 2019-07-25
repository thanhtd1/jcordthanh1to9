<?php

$wrkdir = dirname(__FILE__);
require($wrkdir . '/constants.php');
require($wrkdir . '/functions.php');

//引数チェック
$srcfl = $argv[1];
if (!$srcfl) exitWithError("\nerror: the input file was not specified! \n");
if (!file_exists($srcfl)) exitWithError("\nerror: the specified file (" . $srcfl . ") does not exist! \n");

//ログイン処理
$cookiepath = null;
//$cookiepath = login();
//if (!$cookiepath) exitWithError("\nerror: login failed.") does not exist! \n");

// Windows環境PHP7はこれが必要
if(strpos(PHP_OS, 'WIN') === 0) setlocale(LC_CTYPE, 'C'); 

//JSONデータを取り込んで送信する
$rowcnt = 0;
$srcfp = fopen($srcfl, "r");
while (($itms = fgetcsv($srcfp))) {
    $rowcnt++;
    $webapi = $itms[0];
    $method = strtoupper($itms[1]);
    $mydata = $itms[2];
        
    //送信URL
    if ($method == "GET") $webapi = $webapi . $mydata;
    $url = 'http://' . WEB_SERVER . $webapi;
        
    //送信処理
    if ($method == "GET") {
    	$outjsn = sendGet($url, $cookiepath);
    	
    } else if ($method == "POST") {
    	$outjsn = sendJson($url, $mydata, $cookiepath);
    
    } else {
    	exitWithError("\nerror: unknown method ('" . $method . "') \n");
    }
	
	//結果出力	
	fputcsv(STDOUT, array($webapi, json_encode(json_decode($outjsn, true), JSON_UNESCAPED_UNICODE)));
}
fclose($srcfp);

//Cookieファイルの削除
//unlink($cookiepath);

