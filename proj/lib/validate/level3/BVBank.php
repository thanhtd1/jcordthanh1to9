<?php
//======================================================
//
// 機能名：	ユーザチェック Class.
//
// 機能ID：	BVBank.php
// 継承  ：	none
// 概要  ：	Valid Bank class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank.php");
require_once(DBD_DIR . "dbdBank.php");

require_once(VALID_L1_DIR . "PVType.php");
require_once(VALID_L2_DIR . "FVString.php");
require_once(VALID_L2_DIR . "FVAlnum.php");
require_once(VALID_L2_DIR . "FVAlnumsym.php");
require_once(VALID_L2_DIR . "FVTelnum.php");
require_once(VALID_L2_DIR . "FVZipcode.php");
require_once(VALID_L2_DIR . "FVEmail.php");
require_once(VALID_L2_DIR . "FVDate.php");
require_once(VALID_L2_DIR . "FVDefault.php");
require_once(VALID_L2_DIR . "FVInteger.php");
require_once(VALID_DIR . "ErrorInfo.php");
require_once(VALID_DIR ."Reason.php");
require_once(VALID_DIR . "Valid.php");

class BVBank extends Valid
{
	// 有効フラグ
	private $m_available = [0,1];

	function __construct()
	{
		parent::__construct();
	}

	//======================================================
	// 処理名：	RECID
	// 処理ID：	check_RECID
	// 概要	：	RECIDのチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	// 戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	BANK_RECID_L_LEN	= 1 ;
	const	BANK_RECID_U_LEN	= 16 ;
	function check_RECID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	//======================================================
	//	処理名：	バンクID
	//	処理ID：	check_BANKID
	//	概要  ：	バンクIDのチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	BANK_BANKID_L_LEN	= 1 ;
	const	BANK_BANKID_U_LEN	= 3 ;
	function	check_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	管理バンクID
	//	処理ID：	check_CUR_BANKID
	//	概要  ：	管理バンクIDのチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	BANK_CUR_BANKID_L_LEN	= 1 ;
	const	BANK_CUR_BANKID_U_LEN	= 3 ;
	function	check_CUR_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	有効フラグ
	//	処理ID：	check_AVAILABLE
	//	概要  ：	有効フラグのチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	AVAILABLE_L_LEN	= 1 ;
	const	AVAILABLE_U_LEN	= 1 ;
	function	check_AVAILABLE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	バンク名称
	//	処理ID：	check_BANK_NAME
	//	概要  ：	バンク名称のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	BANK_NAME_L_LEN	= 1 ;
	const	BANK_NAME_U_LEN	= 60 ;
	function	check_BANK_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	省略名称
	//	処理ID：	check_SHORT_NAME
	//	概要  ：	省略名称のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	SHORT_NAME_L_LEN	= 1 ;
	const	SHORT_NAME_U_LEN	= 10 ;
	function	check_SHORT_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	英語名称
	//	処理ID：	check_EMPNAME
	//	概要	：	英語名称のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	EMPNAME_L_LEN	= 1 ;
	const	EMPNAME_U_LEN	= 80;
	function	check_EMPNAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	英語省略名称
	//	処理ID：	check_SHORT_EMPNAME
	//	概要	：	英語省略名称のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	SHORT_EMPNAME_L_LEN	= 1 ;
	const	SHORT_EMPNAME_U_LEN	= 4 ;
	function	check_SHORT_EMPNAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	担当者
	//	処理ID：	check_PERSON
	//	概要	：	担当者のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	PERSON_L_LEN	= 1 ;
	const	PERSON_U_LEN	= 36 ;
	function	check_PERSON($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
	
		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	電話番号
	//	処理ID：	check_TEL_NUM
	//	概要	：	電話番号のチェックをする

	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	TEL_NUM_L_LEN	= 1 ;
	const	TEL_NUM_U_LEN	= 16 ;
	function	check_TEL_NUM($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	FAX番号
	//	処理ID：	check_FAX_NUM
	//	概要	：	FAX番号のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	FAX_NUM_L_LEN	= 1 ;
	const	FAX_NUM_U_LEN	= 16 ;
	function	check_FAX_NUM($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	バンク種別
	//	処理ID：	check_KIND
	//	概要	：	バンク種別のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	KIND_L_LEN	= 1 ;
	const	KIND_U_LEN	= 2 ;
	function	check_KIND($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	バンク並び順
	//	処理ID：	check_ROW_NTH
	//	概要	：	バンク並び順のチェックをする
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	ROW_NTH_L_LEN	= 1 ;
	const	ROW_NTH_U_LEN	= 2 ;
	function	check_ROW_NTH($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		
		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
	}


	function isValid($apd,$prm,&$a_err)
	{
		debug_log(">>()") ;
		debug_log(print_r($apd,True));

		$l_err_info = new ErrorInfo();

		$rtn	= 1;
		$dbd_p	= $apd->getDBDBank();
		// == 単体チェック ==============================================
		$emp = 0;
		if ($prm === 1) {
			$emp = 1;
		}

		debug_log("<< $rtn" );
		return $rtn;
	}

	// Bank 新規データチェック
	function validAdd($apd,&$a_err) {
		return $this->isValid($apd,0,$err);
	}

	// Bank 更新データチェック
	function validMod($apd,&$a_err) {
		return $this->isValid($apd,1,$err);
	}
} // CLASS-EOF
?>
