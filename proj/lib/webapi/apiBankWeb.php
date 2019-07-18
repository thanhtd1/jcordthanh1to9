<?php
//======================================================
//
// 機能名：	サンプル管理 Web用 Api Class.
//
// 機能ID：	apiBank.php
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
require_once(APD_DIR . "apdBank.php");
require_once(LOGIC_DIR . "logicBank.php");
require_once(VALID_L4_DIR . "validBank.php");

class apiBankWeb extends apiCore
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
	// 処理名：	Bankデータ新規入力確認
	// 処理ID：	add
	// 概要  ：	BankデータとDivisionデータを新規入力確認をする。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:パラメータ	(prepare|submit)
	//		[1]:登録データ
	//	$out	:出力データ配列
	//		[0]:出力データ
	//	$err	:エラー内容(戻り値)
	//		
	// 戻り値
	//	1	:正常
	//	0	:異常
	//======================================================
	function add($in,&$out,&$err)
	{
		debug_log(">>()") ;

		$attr	= $in[0];
		debug_log($attr);

		$in[1]['recid']	= 0;	// 初期値

		// APD作成
		$apd	= new apdBank();
		// 登録データ設定
		$apd->convertData($in[1]);

		if ( $attr == "prepare" )
		{
			$out = $apd->getData();

			debug_log("<< ".API_RET_OK) ;
			return API_RET_OK ;
		}

		// 入力チェック
		$out[0]	= $in[1];
		$val = new validBank();
		if ( $val->isValid($apd,1,$err) != 1 )
		{
			debug_log("<< ".API_RET_NG) ;
			return API_RET_NG ;
		}

		debug_log("<< ".API_RET_OK) ;
		return API_RET_OK ;
	}

	//======================================================
	// 処理名：	Bankデータ更新入力確認
	// 処理ID：	upd
	// 概要	 ：	BankデータとDivisionデータの更新入力確認をする。
	//
	// 引数
	//	$in	:入力データ配列
	//		[0]:パラメータ	(prepare|submit)
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

		debug_log(print_r($in,True));
		debug_log(print_r($in[0],True));
		$attr	= $in[0];
		debug_log($attr);

		// APD作成
		$apd	= new apdBank();
		$out_apd = new apdBank();
		// 更新データ設定
		$apd->convertUpdateData($in[1]);

		$l_sess = array();
		$l_sess['USER_ID'] = 2;
		// 初期（DBから最新取得)
		if ( $attr == "prepare" )
		{
			$recid	= $in[1]['recid'];
			//$in[0]  = $recid;
			// 取得実行
			$logic = new logicBank();
			$ret = $logic->getRecId($l_sess, $recid, $out_apd, $err, TRANS_OFF);

				$out = $out_apd->getData();

				debug_log("<< ($ret)") ;
				return ($ret < 0)?API_RET_NG:API_RET_OK ;
		}

		// 入力チェック
		$out[0]	= $in[1];
		$val = new validBank();
		if ( $val->isValid($apd,1,$err) != 1 )
		{
			debug_log("<< ".API_RET_NG) ;
			return API_RET_NG ;
		}

		debug_log("<< ".API_RET_OK) ;
		return API_RET_OK ;
	}
} // CLASS-EOF
?>
