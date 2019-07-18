<?php
//======================================================
//
// 機能名：     Logger lib.
//
// 機能ID：     logger.php
// 継承  ：     none
// 概要  ：     log print api.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

//======================================================
// protect function
function _log_write($name,$str,$ext=False) {

	$nowtime = date("Y/m/d H:i:s");
	$fname = $name . date("Ymd") . LOG_EXT;
	$fp = fopen($fname, "a");

	if ( $ext == True ) {
		$str	  = $_SERVER["REMOTE_ADDR"]
			. " " . $_SERVER["REQUEST_METHOD"]
			. " " . $_SERVER["REQUEST_URI"]
			. " " . $str
		;
	}
	fwrite($fp, $nowtime
		. "(" . getmypid() . ")"    
		. "\t" . $str
		. "\n");
	fclose($fp);
}
function _implode_associative($ary) {

	$wk	= array();
	foreach ($ary as $key => $val)
	{
		$wk[] = $key. ":" . $val;
	}
	return implode(",", $wk);
}
function _explode_associative($str) {

	$ary	= explode(",",$str);
	$dst	= array();
	foreach ($ary as $val)
	{
		$wk = explode(":",$val);
		$dst[$wk[0]]	= $wk[1] ;
//echo "k[" . $wk[0] . "]v[" . $wk[1] . "]<br>";
	}
	return $dst;
}
//======================================================


function get_function($depth=0) {
	$trace	= debug_backtrace() ;

	if ( ! isset($trace[$depth]) )
		return ;

	$file	= isset($trace[$depth]['file']) ? $trace[$depth]['file'] : '' ;
	$line	= isset($trace[$depth]['line']) ? $trace[$depth]['line'] : '' ;

	$arr	= preg_split("/[\/]/",$file) ;
	$file	= end($arr) . ":" . $line ;

	return $file;
}

//======================================================
// 処理名：     debug_log
// 処理ID：     debug_log
// 概要  ：     
//
// 引数
//	$p_str	文字列
// 戻り値
//	
//======================================================
function debug_log($str) {

	if (DEBUG_LOG_IS == 0)
		return;

	$trace	= debug_backtrace() ;

	$ip	= isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '' ;
	$file	= isset($trace[1]['file']) ? $trace[1]['file'] : '' ;
	$line	= isset($trace[1]['line']) ? $trace[1]['line'] : '' ;
	$class	= isset($trace[1]['class']) ? $trace[1]['class'] : '' ;
	$type	= isset($trace[1]['type']) ? $trace[1]['type'] : '' ;
	$func	= isset($trace[1]['function']) ? $trace[1]['function'] : '' ;

	$filel  = sprintf("%20s:%d",$file,$line);
	$info   = $ip . " "
		. $filel . " "
		. $class
		. $type . $func
		. " "
	;

	_log_write(DEBUG_LOG, $info . $str);
}

//======================================================
// 処理名：     connection_log
// 処理ID：     connection_log
// 概要  ：     
//
// 引数
//	$p_str	文字列
// 戻り値
//	
//======================================================
function connection_log($str) {

	_log_write(CONNECTION_LOG,$str);
}

//======================================================
// 処理名：     access_log
// 処理ID：     access_log
// 概要  ：     
//
// 引数
//	
// 戻り値
//	
//======================================================
function access_log() {

	_log_write(ACCESS_LOG,_implode_associative($_GET) . "\t" . _implode_associative($_POST),True);
}

//======================================================
// 処理名：     session_log
// 処理ID：     session_log
// 概要  ：     
//
// 引数
//	
// 戻り値
//	
//======================================================
function session_log($str) {

	if (DEBUG_LOG_IS == 0)
		return;

	_log_write(SESSION_LOG, $str, True);
}

?>
