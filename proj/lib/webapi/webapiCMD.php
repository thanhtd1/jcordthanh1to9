<?php
//======================================================
//
// 機能名：	webapi cmd Class.
//
// 機能ID：	webapiCMD.php
// 継承  ：	none
// 概要  ：	command line interface.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once("../../.config.php");

require_once(TOP_DIR . "lib/smarty/libs/Smarty.class.php");

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

require_once(WEBAPI_DIR . "webapiCore.php");

class webapiCMD extends webapiCore
{
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
		debug_log(">>()") ;
		parent::__construct();
		debug_log("<<") ;
	}

	//public function pre($rtn,$in,&$out,&$err)

	//public function exec($rtn,$in,&$out,&$err)

	//======================================================
	// 処理名：	後処理
	// 処理ID：	post
	// 概要  ：	事後に処理を行う場合の処理ハンドラ
	// 	 	outデータを標準出力を出力を行う。
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

		// head value
		print("err\n");
		print_r($err);

		print("out\n");
		print_r($out);

		debug_log("<< 1") ;
		return 1;
	}

	//======================================================
	// 処理名：	メイン処理
	// 処理ID：	action
	// 概要  ：	各ハンドラの制御を行う。
	// 		開始時に引数を解析し各ハンドラを実行する
	//
	// 引数
	//
	// 戻り値
	//
	//======================================================
	public function main()
	{
		debug_log(">>()") ;
		$out	= array();
		$err	= array();
		$in	= array();

		//
		// PARAM
		//
		$argv = $_SERVER['argv'];
		if ( count($argv) < 4 )
		{
			echo "usage: name func attr (param...)\n";
			return;
		}
		$this->name	= $argv[1];
		$this->func	= $argv[2];
		$in[]		= array($argv[3]);

		//
		// INPUT
		//
		$param	= array();
		for($i=4; count($argv) > 4 && $i<count($argv); $i++)
		{
			$p	= explode("=",$argv[$i]);
			if ( count($p) == 2 )
			{
				$param[ $p[0] ] = $p[1];
			}
		}
		$in[]	= $param;

		//
		// CLASS
		//
		require_once(API_DIR . API_CGI_API . $this->name . API_CGI_EXT);
		$w_class	= API_CGI_API . $this->name ;
		$this->api	= new $w_class();

		//
		// ACTION
		//
		$this->action($in,$out,$err) ;

		debug_log("<<") ;
		return ;
	}
}

// EXIT function
//======================================================
// 処理名：	PHP終了時関数
// 処理ID：	register_shutdown_function
// 概要  ：	異常終了時（システムエラー)時に、
// 	 	エラーを出力する。
//
// 引数
//
// 戻り値
//
//======================================================
register_shutdown_function(
	function() {
		$e = error_get_last();
		if ( isset($e) )
		{
			// PHP error
			print_r($e,True);
		}
	}
);
// EXEC
$obj = new webapiCMD();
$obj->main();
?>