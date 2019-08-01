<?php
//======================================================
//
// 機能名：	システムェック Class.
//
// 機能ID：	BVSystem.php
// 継承  ：	none
// 概要  ：	Valid System class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdSystem.php");
require_once(DBD_DIR . "dbdSystem.php");

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

class BVSystem extends Valid
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
	//		全半角	:
	//		最小桁	:1
	//		最大桁	:16
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
	const	SYSTEM_RECID_L_LEN	= 1 ;
	const	SYSTEM_RECID_U_LEN	= 16;
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
			$ret = FVInteger::validate($a_out[0], self::SYSTEM_RECID_L_LEN, self::SYSTEM_RECID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "RECID", $l_err_info);
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
					//$a_err[] = self::err("種別", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	//		全半角	:
	//		最小桁	:1
	//		最大桁	:1
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
	const	AVAILABLE_L_LEN	= 1 ;
	const	AVAILABLE_U_LEN	= 1 ;
	function check_AVAILABLE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			// 既定値チェック
			$ret = FVDefault::valid($a_out[0], self::AVAILABLE_L_LEN, self::AVAILABLE_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "有効フラグ", $l_err_info, $this->m_def_kind);
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
					//$a_err[] = self::err("種別", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	項目名
	// 処理ID：	check_ITEM_NAME
	// 概要	：	項目名のチェックをする
	//		型	:string
	//		全半角	:
	//		最小桁	:1
	//		最大桁	:16
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
	const	ITEM_NAME_L_LEN	= 1 ;
	const	ITEM_NAME_U_LEN	= 16;
	function check_ITEM_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVString::validate($a_out[0], self::ITEM_NAME_L_LEN, self::ITEM_NAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "入力値", $l_err_info);
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
					//$a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	項目値
	// 処理ID：	check_ITEM_VALUE
	// 概要	：	項目値のチェックをする
	//		型	:string
	//		全半角	:
	//		最小桁	:1
	//		最大桁	:16
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
	const	ITEM_VALUE_L_LEN	= 1 ;
	const	ITEM_VALUE_U_LEN	= 16;
	function check_ITEM_VALUE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVString::validate($a_out[0], self::ITEM_VALUE_L_LEN, self::ITEM_VALUE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "項目値", $l_err_info);
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
					//$a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	備考
	// 処理ID：	check_ITEM_NOTE
	// 概要	：	備考のチェックをする
	//		型	:string
	//		全半角	:
	//		最小桁	:1
	//		最大桁	:40
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
	const	ITEM_NOTE_L_LEN	= 1 ;
	const	ITEM_NOTE_U_LEN	= 40;
	function check_ITEM_NOTE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVString::validate($a_out[0], self::ITEM_NOTE_L_LEN, self::ITEM_NOTE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "備考", $l_err_info);
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
					//$a_err[] = self::err("機関名称", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
					$ret = 1;
				}
			}
		// END Block D Type check
		
		// Block E Finalize
				debug_log("<< ($ret)");
				return $ret;
		// End Block E Finalize
	}
	
} // CLASS-EOF
?>
