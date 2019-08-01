<?php
//======================================================
//
// 機能名：	症例番号管理ェック Class.
//
// 機能ID：	BVBank_caseid.php
// 継承  ：	none
// 概要  ：	Valid Bank_caseid class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank_caseid.php");
require_once(DBD_DIR . "dbdBank_caseid.php");

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

class BVBank_caseid extends Valid
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
	const	BANK_CASEID_RECID_L_LEN	= 1 ;
	const	BANK_CASEID_RECID_U_LEN	= 16 ;
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
		$ret = FVInteger::validate($a_out[0], self::BANK_CASEID_RECID_L_LEN, self::BANK_CASEID_RECID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "RECID", $l_err_info);
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
	const	BANK_CASEID_BANKID_L_LEN	= 1 ;
	const	BANK_CASEID_BANKID_U_LEN	= 16 ;
	function check_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
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
		$ret = FVInteger::validate($a_out[0], self::BANK_CASEID_BANKID_L_LEN, self::BANK_CASEID_BANKID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンクID", $l_err_info);
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
	// 処理名：	バンク毎年毎症例ID
	// 処理ID：	check_SEQ_NO
	// 概要	：	バンク毎年毎症例IDのチェックをする
	//		型	:数値
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
	const	BANK_CASEID_SEQ_NO_L_LEN	= 1 ;
	const	BANK_CASEID_SEQ_NO_U_LEN	= 16 ;
    function check_SEQ_NO($a_val,&$a_out,&$a_err,$a_opt = array())
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
		$ret = FVInteger::validate($a_out[0], self::BANK_CASEID_SEQ_NO_L_LEN, self::BANK_CASEID_SEQ_NO_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンク毎年毎症例ID", $l_err_info);
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
