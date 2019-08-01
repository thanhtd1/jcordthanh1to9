<?php
//======================================================
//
// 機能名：	状態履歴ェック Class.
//
// 機能ID：	BVState.php
// 継承  ：	none
// 概要  ：	Valid State class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdState.php");
require_once(DBD_DIR . "dbdState.php");

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

class BVState extends Valid
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
	const	STATE_RECID_L_LEN	= 1 ;
	const	STATE_RECID_U_LEN	= 16 ;
	//======================================================
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
			$ret = FVInteger::validate($a_out[0], self::STATE_RECID_L_LEN, self::STATE_RECID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "RECID", $l_err_info);
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
	// 処理名：	患者ID
	// 処理ID：	check_RECIPID
	// 概要	：	患者IDのチェックをする
	//		型	:数値
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
	const	STATE_RECIPID_L_LEN	= 1 ;
	const	STATE_RECIPID_U_LEN	= 16;
	function check_RECIPID($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::STATE_RECIPID_L_LEN, self::STATE_RECIPID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "患者ID", $l_err_info);
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
	// 処理名：	さい帯血ID
	// 処理ID：	check_CORDID
	// 概要	：	さい帯血IDのチェックをする
	//		型	:数値
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
	const	STATE_CORDID_L_LEN	= 1 ;
	const	STATE_CORDID_U_LEN	= 16 ;
	function check_CORDID($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::STATE_CORDID_L_LEN, self::STATE_CORDID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "さい帯血ID", $l_err_info);
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
	// 処理名：	状態
	// 処理ID：	check_REG_STAT
	// 概要	：	状態のチェックをする
	//		型	:数値
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
	const	REG_STAT_L_LEN	= 1 ;
	const	REG_STAT_U_LEN	= 2 ;
	function check_REG_STAT($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVDefault::valid($a_out[0], self::REG_STAT_L_LEN, self::REG_STAT_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "状態", $l_err_info, $this->m_def_kind);
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
	// 処理名：	更新ユーザID
	// 処理ID：	check_USER_ID
	// 概要	：	更新ユーザIDのチェックをする
	//		型	:日付
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
	const	STATE_USER_ID_L_LEN	= 1 ;
	const	STATE_USER_ID_U_LEN	= 16;
	function check_USER_ID($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::STATE_USER_ID_L_LEN, self::STATE_USER_ID_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "更新ユーザID", $l_err_info);
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
	// 処理名：	申込日
	// 処理ID：	check_RESERVE_DATE
	// 概要	：	申込日のチェックをする
	//		型	:日付
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
	const	RESERVE_DATE_L_LEN	= 1 ;
	const	RESERVE_DATE_U_LEN	= 12 ;
	function check_RESERVE_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVDate::valid($a_out[0], self::RESERVE_DATE_L_LEN, self::RESERVE_DATE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "申込日", $l_err_info);
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
	// 処理名：	取消日
	// 処理ID：	check_CANCEL_DATE
	// 概要	：	取消日のチェックをする
	//		型	:日付
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
	const	CANCEL_DATE_L_LEN	= 1 ;
	const	CANCEL_DATE_U_LEN	= 12 ;
	function check_CANCEL_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVDate::valid($a_out[0], self::CANCEL_DATE_L_LEN, self::CANCEL_DATE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "取消日", $l_err_info);
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
					//$a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	供給年月日
	// 処理ID：	check_SUPPLY_DATE
	// 概要	：	供給年月日のチェックをする
	//		型	:日付
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
	const	SUPPLY_DATE_L_LEN	= 1 ;
	const	SUPPLY_DATE_U_LEN	= 12 ;
	function check_SUPPLY_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVDate::valid($a_out[0], self::SUPPLY_DATE_L_LEN, self::SUPPLY_DATE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "供給年月日", $l_err_info);
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
					//$a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	供給病院
	// 処理ID：	check_SUPPLY_HOSP
	// 概要	：	供給病院のチェックをする
	//		型	:string
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
	const	SUPPLY_HOSP_L_LEN	= 1 ;
	const	SUPPLY_HOSP_U_LEN	= 50 ;
	function check_SUPPLY_HOSP($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVString::validate($a_out[0], self::SUPPLY_HOSP_L_LEN, self::SUPPLY_HOSP_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "供給病院", $l_err_info);
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
	// 処理名：	施設コード
	// 処理ID：	check_HOSP_CODE
	// 概要	：	施設コードのチェックをする
	//		型	:string
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
	const   HOSP_CODE_L_LEN		= 1 ;
	const   HOSP_CODE_U_LEN		= 20 ;
	function check_HOSP_CODE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVAlnum::valid($a_out[0], self::HOSP_CODE_L_LEN, self::HOSP_CODE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "施設コード", $l_err_info);
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
					//$a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	適合ランク
	// 処理ID：	check_RANK
	// 概要	：	適合ランクのチェックをする
	//		型	:string
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
	const   RANK_L_LEN		= 1 ;
	const   RANK_U_LEN		= 3 ;
	function check_RANK($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVAlnum::valid($a_out[0], self::HOSP_CODE_L_LEN, self::HOSP_CODE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "適合ランク", $l_err_info);
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
					//$a_err[] = self::err("ユーザ名", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
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
	// 処理名：	施設ユーザID
	// 処理ID：	check_USERID
	// 概要	：	施設ユーザIDのチェックをする
	//		型	:数値
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
	const   USERID_L_LEN		= 1 ;
	const   USERID_U_LEN		= 16;
	function check_USERID($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::USERID_L_LEN, self::USERID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "施設ユーザID", $l_err_info);
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
	// 処理名：	カクテル移植フラグ
	// 処理ID：	check_COCKTAIL
	// 概要	：	カクテル移植フラグのチェックをする
	//		型	:数値
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
	const   COCKTAIL_L_LEN		= 1;
	const   COCKTAIL_U_LEN		= 1;
	function check_COCKTAIL($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::COCKTAIL_L_LEN, self::COCKTAIL_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "カクテル移植フラグ", $l_err_info);
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
	// 処理名：	検索数
	// 処理ID：	check_SEARCH_NUMBER
	// 概要	：	検索数のチェックをする
	//		型	:数値
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
	const   SEARCH_NUMBER_L_LEN		= 1;
	const   SEARCH_NUMBER_U_LEN		= 1;
	function check_SEARCH_NUMBER($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::SEARCH_NUMBER_L_LEN, self::SEARCH_NUMBER_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "検索数", $l_err_info);
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
	// 処理名：	適合数
	// 処理ID：	check_SEARCH_NUMBER
	// 概要	：	適合数のチェックをする
	//		型	:数値
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
	const   FIT_NUMBER_L_LEN		= 1;
	const   FIT_NUMBER_U_LEN		= 1;
	function check_FIT_NUMBER($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVInteger::validate($a_out[0], self::FIT_NUMBER_L_LEN, self::FIT_NUMBER_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "適合数", $l_err_info);
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
	// 処理名：	備考
	// 処理ID：	check_SEARCH_NUMBER
	// 概要	：	備考のチェックをする
	//		型	:string
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
	const   NOTE_L_LEN		= 1  ;
	const   NOTE_U_LEN		= 100;
	function check_NOTE($a_val,&$a_out,&$a_err,$a_opt = array())
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
			$ret = FVString::validate($a_out[0], self::NOTE_L_LEN, self::NOTE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "備考", $l_err_info);
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
