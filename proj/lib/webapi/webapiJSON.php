<?php
//======================================================
//
// 機能名：	webapi JSON Class.
//
// 機能ID：	webapiJSON.php
// 継承  ：	none
// 概要  ：	json(http) interface.
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

class webapiJSON extends webapiCore
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
	// 	 	outデータを元にJSON出力を行う。
	//		書式はSmartyを使用し、名称は以下とする。
	//		$this->name . "_" . $this->func . ".tpl"
	//		cache無しのヘッダをつ行ける
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
		//debug_log(print_r($pq,True));
		//debug_log(print_r($pp,True));
		//debug_log(print_r($pr,True));

		$v_obj			= new Smarty();
		$v_obj->caching		= 0;
		$v_obj->debugging	= false;
		$v_obj->cache_lifetime	= 120;
		$v_obj->template_dir	= TMP_DIR;
		$v_obj->compile_dir	= CMP_DIR;

		// head value
		debug_log(print_r($err,True));
		$v_obj->assign("head",$err);

		// data value
		debug_log(print_r($out,True));
		if(isset($out['total'])){
			$v_obj->assign("total",$out['total']);
			unset($out['total']);
		}
		$v_obj->assign("data",$out);

		// output
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

		if($this->func == "getBankid"){
			$v_obj->display($this->name . "_get.tpl");
		}
		else{
			$v_obj->display($this->name . "_" . $this->func . ".tpl");
		}

		debug_log("<< 1") ;
		return 1;
	}

	//======================================================
	// 処理名：	メイン処理
	// 処理ID：	action
	// 概要  ：	各ハンドラの制御を行う。
	// 	 	開始時にパラメータ(URI)とJSONデータを解析し
	//		各ハンドラを実行する
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
		$json	= null ;

		//
		// PARAM
		//
		//foreach ( $_SERVER as $k =>$v ){	debug_log("SERVER $k:$v");	}
		if ( ! isset( $_SERVER['REQUEST_URI'] ) )
		{
			return ;
		}
		$p_param	= $_SERVER['REQUEST_URI'];
		debug_log("param:".$p_param) ;
		//echo "REQUEST_URI:$p_param\n";

		$this->name	= null ;
		$this->func	= null ;

		//
		// INPUT
		//
		// [get]
		if ( preg_match( "/\/lib\/webapi\/member\/([^\/]+)\/([^\/]+)\/([0-9]+)/",$p_param,$match ) || preg_match( "/\/lib\/webapi\/bank\/([^\/]+)\/([^\/]+)\/([0-9]+)/",$p_param,$match ) )
		{
			$this->name	= $match[1];
			$this->func	= $match[2];
			$in[]		= $match[3];
		}
		// [query]
		else if ( preg_match( "/\/lib\/webapi\/member\/([^\/]+)\/([^\?]*)\/(query)\?/",$p_param,$match ) || preg_match( "/\/lib\/webapi\/admin\/([^\/]+)\/([^\?]*)\/(query)\?/",$p_param,$match ) )
		{
			$this->name	= $match[1];
			$this->func	= $match[2];
			$in[]		= $match[3];
		 	foreach ( $_GET as $k =>$v )
			{
				debug_log("GET $k:$v");

				if ($k == 'q')	  {  $in[]	= json_decode($v);	}
				if ($k == 'p')	  {  $in[]	= json_decode($v);	}
				if ($k == 'r')	  {  $in[]	= json_decode($v);	}
			}
		}
		// other
		else if ( preg_match( "/\/lib\/webapi\/member\/([^\/]+)\/([^\?]*)\/([^\?]*)/",$p_param,$match ) || preg_match( "/\/lib\/webapi\/bank\/([^\/]+)\/([^\?]*)\/([^\?]*)/",$p_param,$match ) )
		{
			$this->name	= $match[1];
			$this->func	= $match[2];
			$in[]		= $match[3];

			$json	= json_decode(file_get_contents('php://input'),true);
			debug_log(print_r($json,True));

			$in[]		= $json;
		}
		debug_log("MATCH other($this->name:$this->func)") ;

		//
		// CLASS
		//
		require_once(WEBAPI_DIR . API_CGI_API . $this->name . API_CGI_EXT);
		$w_class	= API_CGI_API . $this->name ;
		$this->api	= new $w_class();

		//
		// ACTION
		//
		$rtn = $this->action($in,$out,$err) ;

		debug_log("<<") ;
		return ;
	}
}

// EXIT function
//======================================================
// 処理名：	PHP終了時関数
// 処理ID：	register_shutdown_function
// 概要  ：	異常終了時（システムエラー)時に、
// 	 	エラー用のJSONを出力する。
//		書式はSmartyを使用し、名称は以下とする。
//		Error.tpl
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
			debug_log(print_r($e,True));

			$v_obj			= new Smarty();
			$v_obj->caching		= 0;
			$v_obj->debugging	= false;
			$v_obj->cache_lifetime	= 120;
			$v_obj->template_dir	= TMP_DIR;
			$v_obj->compile_dir	= CMP_DIR;

			// head value
			$v_obj->assign("head",array('status'=>'FAILED','message'=>'failed'));

			$v_obj->display("Error.tpl");
		}
	}
);
// EXEC
$obj = new webapiJSON();
$obj->main();
?>
