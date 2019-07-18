<?php

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(WEBAPI_DIR . "apiCore.php");
require_once(LOGIC_DIR . "logicBank.php");
require_once(APD_DIR . "apdBank.php");

class apiBank extends apiCore
{
	function __construct()
	{
	}

	function add($in,&$out,&$err)
	{
		debug_log(">>()") ;

		// APD作成
		$in_apd = new apdBank();
		$out_apd = new apdBank();
		// 登録データ設定
		$in_apd->convertData($in[1]);

		$l_sess = array();
		$l_sess['USER_ID'] = 1;

		// 登録ロジック実行
		$logic = new logicBank();
		$ret = $logic->add($l_sess, $in_apd,$out_apd,$err);
	
		$out = $out_apd->getData();

		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}

	//======================================================
	// 処理名：	Bankデータ更新
	// 処理ID：	upd
	// 概要	 ：	Bankデータを更新する。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:パラメータ	(commit)
	//		[1]:更新データ
	//	$out	:出力データ配列
	//		[0]:出力データ
	//	$err	:エラー内容(戻り値)
	//
	// 戻り値
	//	1	:正常
	//	0	:異常
	//======================================================
	function upd($in,&$out,&$err)
	{
		debug_log(">>()") ;

		// APD作成
		$in_apd = new apdBank();
		$out_apd = new apdBank();
		// 更新データ設定
		$in_apd->convertUpdateData($in[1]);

		$l_sess = array();
		$l_sess['USER_ID'] = 2;

		// 更新実行
		$logic = new logicBank();
		$ret = $logic->upd($l_sess, $in_apd, $out_apd, $err);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}


		//======================================================
	// 処理名：	Bankデータ削除
	// 処理ID：	del
	// 概要  ：	Bankデータを削除する。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:パラメータ	(delete)
	//		[1]:更新データ
	//	$out	:出力データ配列
	//		[0]:出力データ
	//	$err	:エラー内容(戻り値)
	//		
	// 戻り値
	//	1	:正常
	//	0	:異常
	//======================================================
	function del($in,&$out,&$err)
	{
		debug_log(">>()") ;

		// APD作成
		$in_apd = new apdBank();
		$out_apd = new apdBank();
		// 更新データ設定
		$in_apd->convertUpdateData($in[1]);

		// 削除実行
		//TODO::
		//$ret	= 1;
		$l_sess = array();
		$l_sess['USER_ID'] = 2;
		// 更新実行
		$logic = new logicBank();
		$ret = $logic->del($l_sess, $in_apd, $out_apd, $err);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}


	function list($in,&$out,&$err)
	{
		debug_log(print_r($in,True));

		debug_log("param:". $in[0]);

		$logic = new logicBank();

		$where = array();
		$order = array();
		$where = $in[1];
		$order = $in[2];
		$ret = $logic->list( $where, $order, $out, $err, TRANS_OFF);
		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}
	
    function getBankid($in, &$out, &$err)
	{
		// APD作成
		$out_apd = new apdBank();
		$recid = $in[0];


		// 取得実行
		$logic = new logicBank();
		$ret = $logic->getBankid($recid, $out_apd, $err, TRANS_OFF);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}

	function get($in, &$out, &$err)
	{
		// APD作成
		$out_apd = new apdBank();
		$recid = $in[0];
		// 
		$l_sess = array();
		$l_sess['USER_ID'] = 2;

		// 取得実行
		$logic = new logicBank();
		$ret = $logic->getRecId($l_sess, $recid, $out_apd, $err, TRANS_OFF);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}
}
?>
