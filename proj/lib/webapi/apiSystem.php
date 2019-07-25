<?php
//======================================================
//
// 機能名：	サンプル管理 Api Class.
//
// 機能ID：	apiSystem.php
// 継承  ：	none
// 概要  ：	Api class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(WEBAPI_DIR . "apiCore.php");
require_once(APD_DIR . "apdSystem.php");
require_once(LOGIC_DIR . "logicSystem.php");

class apiSystem extends apiCore
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
	}

	//======================================================
	// 処理名：	Systemデータ1件取得
	// 処理ID：	get
	// 概要  ：	Systemデータを取得する。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:RECID
	//	$out	:出力データ配列
	//		[0]:出力データ
	//	$err	:エラー内容(戻り値)
	//
	// 戻り値
	//	 1	  :正常
	//	 0	  :異常
	//======================================================
	function get($in, &$out, &$err)
	{
		// APD作成
		$out_apd = new apdSystem();
		$recid = $in[0];

		$l_sess = array();

		// 取得実行
		$logic = new logicSystem();
		$ret = $logic->get($l_sess, $recid, $out_apd, $err, TRANS_OFF);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
    }
    
    //======================================================
	// 処理名：	Systemデータ1件取得
	// 処理ID：	getItem
	// 概要  ：	Systemデータを取得する。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]: item_name
	//	$out	:出力データ配列
	//		[0]:出力データ
	//	$err	:エラー内容(戻り値)
	//
	// 戻り値
	//	 1	  :正常
	//	 0	  :異常
	//======================================================
	function getItem($in, &$out, &$err)
	{
		// APD作成
		$out_apd = new apdSystem();
		$item_name = $in[0];

		$l_sess = array();
		
		// 取得実行
		$logic = new logicSystem();
		$ret = $logic->getItem($l_sess, $item_name, $out_apd, $err, TRANS_OFF);

		$out = $out_apd->getData();

		debug_log("<< ($ret)") ;
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}

	//======================================================
	// 処理名：	Systemデータ取得
	// 処理ID：	list
	// 概要  ：	Systemデータを取得する。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:パラメータ
	//		[1]:検索条件の配列
	//		[2]:ページ条件の配列
	//		[3]:ソート順条件の配列
	//	$out	:出力データ配列
	//		[...]:出力データ
	//	$err	:エラー内容(戻り値)
	//
	// 戻り値
	//	 1	  :正常
	//	 0	  :異常
	//======================================================
	function list($in,&$out,&$err)
	{
		debug_log(print_r($in,True));

		debug_log("param:". $in[0]);

		$l_sess = array();

		// 検索実行
		$logic = new logicSystem();

		$where = array();
		$order = array();
		$where = $in[1];
        $order = $in[2];
        $a_out = [];
        $a_err = [];
		$ret = $logic->list($l_sess, $where, $order, $out, $err, TRANS_OFF);
		$all_list = $logic->list($l_sess, $where, null, $a_out, $a_err, TRANS_OFF);
		$total = count($a_out);
		$out['total'] = $total;
		debug_log("<< ($ret)");
		return ($ret < 0)?API_RET_NG:API_RET_OK ;
	}
} // CLASS-EOF
?>
