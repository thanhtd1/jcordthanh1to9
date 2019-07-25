<?php

/**
 * ランダム文字列生成 (英数字)
 *
 * $length: 生成する文字数
 */
function randomString($length) {
    $chars = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $charcnt = count($chars);
    $rslt = null;
    for ($i = 0; $i < $length; $i++) {
        $rslt .= $chars[rand(0, $charcnt - 1)];
    }
    return $rslt;
}

/**
 * 時刻つきの結果ファイル名を取得する
 *
 * $prefix: ファイル名の接頭辞
 * $suffix: 拡張子などの接尾辞
 */
function resultName($prefix, $suffix) {
	return $prefix . date("YmdHis") . $suffix;
}

/**
 * リモートサーバーへのログイン処理を行う
 *
 * @return: 成功した場合はクッキーファイルのパス、失敗した場合は''
 */
function login() {
	$wrkdir = dirname(__FILE__);
	
	//クッキーファイルの作成
	$cookiepath = $wrkdir . '/cookie_' . randomString(16);
	//touch($cookiepath);

	//ログインURLとログインパラメーター
	$params = array( 
	    "username" => 'cordblood', 
	    "password" => 'chou', 
	); 

	//ログイン処理    
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_URL, "http://localhost/cordblood/reception.php");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
	$response = curl_exec($ch);
	$header = curl_getinfo($ch);
	$status = $header['http_code'];
	curl_close($ch);
	
	//結果の判定
	return $status == 200 ? $cookiepath : ''; 
}

/**
 * JSONデータの送信処理
 *
 * $url: 送信先
 * $json: JSONデータ(文字列)
 * $cookie: クッキーデータのファイルパス
 *
 * $return: レスポンスデータ
 */
function sendJson($url, $json, $cookie) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	if ($cookie) curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	$response = curl_exec($ch);
	curl_close($ch);
	
	return $response;
}

/**
 * HTTPのGETリクエストの送信処理
 *
 * $url: 送信先
 * $cookie: クッキーデータのファイルパス
 *
 * $return: レスポンスデータ
 */
function sendGet($url, $json) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	//if ($cookie) curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	$response = curl_exec($ch);
	curl_close($ch);
	
	return $response;
}

/**
 * エラー出力と全終了
 *
 * $errmsg: エラーメッセージ
 */
function exitWithError($errmsg) {
	fputs(STDERR, $errmsg); 
	exit;
}


