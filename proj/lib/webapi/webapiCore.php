<?php
//======================================================
//
// 機能名：	webapi Core Class.
//
// 機能ID：	webapiCore.php
// 継承  ：	none
// 概要  ：	webapi core class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

//require_once("../../.config.php");

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

class webapiCore
{
	public	$name	= null ;	// API名称
	public	$func	= null ;	// 処理名
	public	$api	= null ;	// api object

	//======================================================
	// 処理名：	コンストラクタ
	// 処理ID：	__construct
	// 概要  ：	
	//
	// 引数
	//		
	// 戻り値
	//
	//======================================================
	function __construct()
	{
	}

	//======================================================
	// 処理名：	前処理
	// 処理ID：	pre
	// 概要  ：	事前に処理を行う場合の処理ハンドラ
	//
	// 引数
	//		$rtn	:int	前処理のエラーコード、必要に応じて使用する
	//		$in	:array	入力配列
	//		$out	:array	出力配列
	//		$err	:array	エラー配列(key,value)
	// 戻り値
	//		1:正常
	//		0:以上
	//
	//======================================================
	public function pre($rtn,$in,&$out,&$err)
	{
		debug_log(">>($rtn)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		debug_log("<< 1") ;
		return 1;
	}

	//======================================================
	// 処理名：	実処理
	// 処理ID：	pre
	// 概要  ：	処理を行う場合の処理ハンドラ
	//
	// 引数
	//		$rtn	:int	前処理のエラーコード、必要に応じて使用する
	//		$in	:array	入力配列
	//		$out	:array	出力配列
	//		$err	:array	エラー配列(key,value)
	// 戻り値
	//		1:正常
	//		0:以上
	//
	//======================================================
	public function exec($rtn,$in,&$out,&$err)
	{
		debug_log(">>($rtn)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		$ret = ($this->api)->{$this->func}($in,$out,$err);

		debug_log("<< $ret") ;
		return $ret ;
	}

	//======================================================
	// 処理名：	後処理
	// 処理ID：	post
	// 概要  ：	事後に処理を行う場合の処理ハンドラ
	//
	// 引数
	//		$rtn	:int	前処理のエラーコード、必要に応じて使用する
	//		$in	:array	入力配列
	//		$out	:array	出力配列
	//		$err	:array	エラー配列(key,value)
	// 戻り値
	//		1:正常
	//		0:以上
	//
	//======================================================
	public function post($rtn,$in,&$out,&$err)
	{
		debug_log(">>($rtn)") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		debug_log("<< 1") ;
		return 1;
	}

	//======================================================
	// 処理名：	メイン処理
	// 処理ID：	action
	// 概要  ：	各ハンドラの制御を行う。
	//
	// 引数
	//		$in	:array	入力配列
	//		$out	:array	出力配列
	//		$err	:array	エラー配列(key,value)
	// 戻り値
	//
	//======================================================
	public function action($in,&$out,&$err)
	{
		debug_log(">>()") ;
		//debug_log(print_r($in,True));
		//debug_log(print_r($out,True));
		//debug_log(print_r($err,True));

		$rtn	= 1;
		$rtn = $this->pre($rtn,$in,$out,$err) ;
		debug_log("<< (pre:$rtn)") ;

		$rtn = $this->exec($rtn,$in,$out,$err) ;
		debug_log("<< (pre:$rtn)") ;

		$rtn = $this->post($rtn,$in,$out,$err) ;
		debug_log("<< (pre:$rtn)") ;

		debug_log("<<") ;
		return ;
	}
}
?>
