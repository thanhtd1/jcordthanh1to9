<?php
//======================================================
//
// 機能名：	バンクェック Class.
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
require_once(VALID_L2_DIR . "FVAlpha.php");
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
	function __construct()
	{
		parent::__construct();
	}

	//======================================================
	// 処理名：	RECID
	// 処理ID：	check_RECID
	// 概要	：	RECIDのチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 16
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_RECID_L_LEN	= 1 ;
	const	BANK_RECID_U_LEN	= 16 ;
	function check_RECID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_RECID_L_LEN, self::BANK_RECID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "RECID", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

	
	//======================================================
	// 処理名：	バンクID
	// 処理ID：	check_BANKID
	// 概要	：	バンクIDのチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 3
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_BANKID_L_LEN	= 1 ;
	const	BANK_BANKID_U_LEN	= 3 ;
	function	check_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_BANKID_L_LEN, self::BANK_BANKID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンクID", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	管理バンクID
	// 処理ID：	check_CUR_BANKID
	// 概要	：	管理バンクIDのチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 3
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_CUR_BANKID_L_LEN	= 1 ;
	const	BANK_CUR_BANKID_U_LEN	= 3 ;
	function	check_CUR_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_CUR_BANKID_L_LEN, self::BANK_CUR_BANKID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "管理バンクID", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	有効フラグ
	// 処理ID：	check_AVAILABLE
	// 概要	：	有効フラグのチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 1
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_AVAILABLE_L_LEN	= 1 ;
	const	BANK_AVAILABLE_U_LEN	= 1 ;
	function	check_AVAILABLE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_AVAILABLE_L_LEN, self::BANK_AVAILABLE_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "有効フラグ", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

	//======================================================
	//	処理名：	バンク名称
	//	処理ID：	check_BANK_NAME
	//	概要	：	バンク名称のチェックをする
	//		型	:文字列
	//		全半角	:
	//		最小桁	: 1
	//		最大桁	: 30
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	BANK_NAME_L_LEN	= 1 ;
	const	BANK_NAME_U_LEN	= 30;
	function	check_BANK_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::BANK_NAME_L_LEN, self::BANK_NAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "バンク名称", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			  $a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	省略名称
	// 処理ID：	check_SHORT_NAME
	// 概要	：	省略名称のチェックをする
	//		型	:文字列
	//		全半角	:
	//		最小桁	: 1
	//		最大桁	: 5
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	SHORT_NAME_L_LEN	= 1 ;
	const	SHORT_NAME_U_LEN	= 5;
	function	check_SHORT_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::SHORT_NAME_L_LEN, self::SHORT_NAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "省略名称", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			  $a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	英語名称
	// 処理ID：	check_ENAME
	// 概要	：	英語名称のチェックをする
	//		 型	:半角英数字
	//		 全半角	:半角
	//		 最小桁	: 1
	//		 最大桁	: 80
	//		 最小値	:
	//		 最大値	:
	//		 範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	ENAME_L_LEN	= 1 ;
	const	ENAME_U_LEN	= 80;
	function	check_ENAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 半角英数字チェック
		$ret = FVAlpha::valid($a_out[0], self::ENAME_L_LEN, self::ENAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "英語名称", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	英語省略名称
	// 処理ID：	check_SHORT_ENAME
	// 概要	：	英語省略名称のチェックをする
	//		 型	:半角英数字
	//		 全半角	:半角
	//		 最小桁	: 1
	//		 最大桁	: 4
	//		 最小値	:
	//		 最大値	:
	//		 範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	SHORT_ENAME_L_LEN	= 1 ;
	const	SHORT_ENAME_U_LEN	= 4 ;
	function	check_SHORT_ENAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 半角英数字チェック
		$ret = FVAlpha::valid($a_out[0], self::SHORT_ENAME_L_LEN, self::SHORT_ENAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "英語省略名称", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	担当者
	// 処理ID：	check_PERSON
	// 概要	：	担当者のチェックをする
	//		型	:文字列
	//		全半角	:
	//		最小桁	: 1
	//		最大桁	: 18
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	PERSON_L_LEN	= 1 ;
	const	PERSON_U_LEN	= 18;
	function	check_PERSON($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::PERSON_L_LEN, self::PERSON_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "担当者", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			  $a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	電話番号
	// 処理ID：	check_TEL_NUM
	// 概要	：	電話番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	: 1
	//		最大桁	: 16
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	TEL_NUM_L_LEN	= 1 ;
	const	TEL_NUM_U_LEN	= 16 ;
	function	check_TEL_NUM($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 電話番号チェック
		$ret = FVTelnum::valid($a_out[0], self::TEL_NUM_L_LEN, self::TEL_NUM_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "電話番号", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			$a_err[] = self::err("電話番号", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	FAX番号
	// 処理ID：	check_FAX_NUM
	// 概要	：	FAX番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	: 1
	//		最大桁	: 16
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	FAX_NUM_L_LEN	= 1 ;
	const	FAX_NUM_U_LEN	= 16 ;
	function	check_FAX_NUM($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		// Block A Initialize
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 電話番号チェック
		$ret = FVTelnum::valid($a_out[0], self::FAX_NUM_L_LEN, self::FAX_NUM_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "FAX番号", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			$a_err[] = self::err("電話番号", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		debug_log("<< ($ret)");
		return $ret;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	バンク種別
	// 処理ID：	check_KIND
	// 概要	：	バンク種別のチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 2
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_KIND_L_LEN	= 1 ;
	const	BANK_KIND_U_LEN	= 2 ;
	function	check_KIND($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_KIND_L_LEN, self::BANK_KIND_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンク種別", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

	//======================================================
	// 処理名：	バンク並び順
	// 処理ID：	check_ROW_NTH
	// 概要	：	バンク並び順のチェックをする
	//		型	:数値
	//		全半角	: 半角
	//		最小桁	: 1
	//		最大桁	: 2
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる
	//	$a_err	:エラーの内容を配列で返す。
	//	$a_opt	:オプションを二次元配列で指定する。
	//	　　　　　必須の場合：key = required value = 1:必須,0:任意
	//	　　　　　Normalizeの場合：key = normalize value = NMZ_NONE:Normalizeしない、
	//	　　　　　NMZ_INT_HAN～NMZ_ZEN_HIRA_ZEN_KANAまでを組み合わせてNormalizeを行う。
	//	　　　　　例) 半角カナを全角ひらがなへ、数字を半角数字へ変換したい場合。
	//	　　　　　　　"normalize" => self::NMZ_HAN_KANA_ZEN_HIRA . self::NMZ_INT_HAN
	//	　　　　　　　の様に連結させる。
	//
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	BANK_ROW_NTH_L_LEN	= 1 ;
	const	BANK_ROW_NTH_U_LEN	= 2 ;
	function	check_ROW_NTH($a_val,&$a_out,&$a_err,$a_opt = array())
	{
// Block A Initialize
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;
// End Block A Initialize

// Block B Option analyze
		if ($a_opt != null && 0 < count($a_opt)) {
			if (isset($a_opt['required'])) {
				$l_required = $a_opt['required'];
			}
			if (isset($a_opt['normalize'])) {
				$l_normalize = $a_opt['normalize'];
			}
		}

		if($a_out != null && 0 < count($a_out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($a_out[0]);
				return 0;
			} else {
				$a_out[0] = $a_val;
			}
		}
		else {
			$a_out[0] = $a_val;
		}
// End Block B Option analyze

// Block C Normalize
		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}
// End Block C Normalize

// Block D Type check
		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::BANK_ROW_NTH_L_LEN, self::BANK_ROW_NTH_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンク並び順", $l_err_info);
		// チェックエラー
		if ($ret < 0) {
			$err_count = count($l_err_info->errors);
			for($i = 0; $i < $err_count; $i++) {
				$err_reason = $l_err_info->errors[$i];
				$a_err[] = self::err($err_reason->what,$err_reason->how,$err_reason->why,$err_reason->level);
			}
		}
		// チェック正常
		else {
			// Normalizeされている場合はNormalizeされた値を返す。WARNINNGも返す？
			if ($l_normalize !== self::NMZ_NONE) {
//			      $a_err[] = self::err("RECID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}
// END Block D Type check

// Block E Finalize
		// エラーなし
		debug_log("<<") ;
		return $ret ;
// End Block E Finalize
	}

} // CLASS-EOF
?>
