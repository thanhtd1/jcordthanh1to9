<?php

$wrkdir = dirname(__FILE__);
require($wrkdir . '/constants.php');
require($wrkdir . '/functions.php');

const CLEAR = "\e[0m";
const ERROR = "\e[41;97m";
const WARNING = "\e[43;30m";
const OK      = "\e[42;30m";
//引数チェック
$srcfl = $argv[1];
if (!$srcfl) exitWithError("\nerror: the input file was not specified! \n");
if (!file_exists($srcfl)) exitWithError("\nerror: the specified file (" . $srcfl . ") does not exist! \n");
$allow_options = ['GET','POST','-JSON'];
$options = [];
for($i = 2; $i < $argc; $i++){
    $options[] = strtoupper($argv[$i]);
}
$json = false;
if(in_array('-JSON', $options)){
    $json = true;
}
foreach ($options as $key => $value) {
    if(!in_array($value, $allow_options)){
        echo ERROR."Options ".CLEAR.WARNING.$value.CLEAR.ERROR." are not  supported.".CLEAR;die;
    }
    elseif($value != '-JSON'){
        $method = strtoupper($value);
    }
}
//ログイン処理
//$cookiepath = login();
//if (!$cookiepath) exitWithError("\nerror: login failed.") does not exist! \n");

//JSONデータを取り込んで送信する
$rowcnt = 0;
$srcfp = fopen($srcfl, "r");
while (($itms = fgetcsv($srcfp))) {
    $rowcnt++;
    $webapi = $itms[0];
    if(isset($itms[1])){
        $srcjsn = $itms[1];
    }
    else{
        $srcjsn = '';
    }
    // convert query to json
    if(isset($method) && $method == 'GET' && $srcjsn != ''){
        $query = [];
        foreach (explode('&',$srcjsn) as $key => $value) {
            $item = explode('=',$value);
            $item[1] = urlencode($item[1]);
            $query []= implode("=",$item);
        }
        $query = implode("&",$query);
        $webapi .="?" .  $query;
        $srcjsn = null;
    }
    $url = 'http://' . WEB_SERVER . $webapi;
	$outjsn = sendJson($url, $srcjsn, null);
	//$outjsn = sendJson($url, $srcjsn, $cookiepath);
    //fputcsv(STDOUT, array($webapi, trim($outjsn)));
    if(json_decode($outjsn, true)['head']['status']=='SUCCEEDED'){
        $start_color = OK;
    }
    else{
        $start_color = ERROR;
    }
    $dash = '';
    for($i=0; $i <strlen($url) + 2; $i++){
        $dash .= "-";
    }
    echo $dash ."\n|" .$start_color .$url .CLEAR ."|\n";
    if(!$json){
        print_r($outjsn);
    }
    else{
        fputcsv(STDOUT, array(json_encode(json_decode($outjsn, true), JSON_UNESCAPED_UNICODE)));
    }
}
fclose($srcfp);

//Cookieファイルの削除
//unlink($cookiepath);

