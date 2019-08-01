<?php
//======================================================
//
// 機能名：	ユーザチェック Class.
//
// 機能ID：	BVUser.php
// 継承  ：	none
// 概要  ：	Valid User class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdUser.php");
require_once(DBD_DIR . "dbdCord_user.php");

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

class BVUser extends Valid
{
	const KIND_ADMIN = 4;
	const KIND_BANK = 5;
	// 種別の既定値
	private $m_def_kind = [4,5];
	// ロックフラグの規定値
	private $m_def_lock_flag = [0,1];

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
	//		最小桁	:
	//		最大桁	:
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
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	const	USER_RECID_L_LEN	= 1 ;
	const	USER_RECID_U_LEN	= 16 ;
	function check_RECID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::USER_RECID_L_LEN, self::USER_RECID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "RECID", $l_err_info);
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

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	//======================================================
	//	処理名：	バンクID
	//	処理ID：	check_BANKID
	//	概要  ：	バンクIDのチェックをする
	//		型	:数値
	//		全半角	:半角
	//		最小桁	:
	//		最大桁	:
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$a_val	:入力値
	//	$a_out	:配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	//	$a_err	:エラーの内容を配列で返す。エラーの内容を配列で返す。
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
	const	USER_BANKID_L_LEN	= 1 ;
	const	USER_BANKID_U_LEN	= 16 ;
	function	check_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 数値チェック
		$ret = FVInteger::validate($a_out[0], self::USER_BANKID_L_LEN, self::USER_BANKID_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "バンクID", $l_err_info);
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
//				$a_err[] = self::err("バンクID", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	// 処理名：	ユーザ名
	// 処理ID：	check_USER_NAME
	// 概要	：	ユーザ名のチェックをする
	//		 型	:半角英数字
	//		 全半角	:半角
	//		 最小桁	:1
	//		 最大桁	:12
	//		 最小値	:
	//		 最大値	:
	//		 範囲	:
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
	// 戻り値
	//	 0	:正常
	//	 0以外	:エラーコード
	//======================================================
	const   USER_NAME_L_LEN		= 1 ;
	const   USER_NAME_U_LEN		= 12 ;
	function check_USER_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 半角英数字チェック
		$ret = FVAlnum::valid($a_out[0], self::USER_NAME_L_LEN, self::USER_NAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ユーザ名", $l_err_info);
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

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	パスワード
	//	処理ID：	check_PASSWD
	//	概要  ：	パスワードのチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:8
	//		最大桁	:16
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
	const	PASSWD_L_LEN	 = 8 ;
	const	PASSWD_U_LEN	 = 16 ;
	function	check_PASSWD($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 半角英数字記号チェック
		$ret = FVAlnumsym::valid($a_out[0], self::PASSWD_L_LEN, self::PASSWD_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "パスワード", $l_err_info);
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
//			    $a_err[] = self::err("パスワード", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	機関名称
	//	処理ID：	check_ORG_NAME
	//	概要	：	機関名称のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:40
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
	const	ORG_NAME_L_LEN	= 1 ;
	const	ORG_NAME_U_LEN	= 40 ;
	function	check_ORG_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::ORG_NAME_L_LEN, self::ORG_NAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "機関名称", $l_err_info);
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

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	所属
	//	処理ID：	check_EMPNAME
	//	概要	：	所属のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:30
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
	const	EMPNAME_L_LEN	= 1 ;
	const	EMPNAME_U_LEN	= 30 ;
	function	check_EMPNAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::EMPNAME_L_LEN, self::EMPNAME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "所属", $l_err_info);
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
//			$a_err[] = self::err("所属", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	担当者
	//	処理ID：	check_PERSON
	//	概要	：	担当者のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:18
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
	const	PERSON_L_LEN	= 1 ;
	const	PERSON_U_LEN	= 18 ;
	function	check_PERSON($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

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
//			$a_err[] = self::err("担当者", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ふりがな
	//	処理ID：	check_FURIGANA
	//	概要	：	ふりがなのチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:21
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
	const	FURIGANA_L_LEN	= 1 ;
	const	FURIGANA_U_LEN	= 21 ;
	function	check_FURIGANA($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::FURIGANA_L_LEN, self::FURIGANA_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ふりがな", $l_err_info);
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
//			$a_err[] = self::err("ふりがな", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	電話番号
	//	処理ID：	check_TEL_NUM1
	//	概要	：	電話番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:16
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
	const	TEL_NUM1_L_LEN	= 1 ;
	const	TEL_NUM1_U_LEN	= 16 ;
	function	check_TEL_NUM1($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 電話番号チェック
		$ret = FVTelnum::valid($a_out[0], self::TEL_NUM1_L_LEN, self::TEL_NUM1_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "電話番号", $l_err_info);
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

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	内線番号
	//	処理ID：	check_TEL_NUM2
	//	概要	：	内線番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:10
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
	const	TEL_NUM2_L_LEN	= 1 ;
	const	TEL_NUM2_U_LEN	= 10 ;
	function	check_TEL_NUM2($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 半角英数字記号チェック
		$ret = FVAlnumsym::valid($a_out[0], self::TEL_NUM2_L_LEN, self::TEL_NUM2_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "内線番号", $l_err_info);
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
//			  $a_err[] = self::err("内線番号", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	FAX番号
	//	処理ID：	check_FAX_NUM
	//	概要	：	FAX番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:16
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
	const	FAX_NUM_L_LEN	= 1 ;
	const	FAX_NUM_U_LEN	= 16 ;
	function	check_FAX_NUM($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// FAX番号チェック
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
//			$a_err[] = self::err("FAX番号", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	郵便番号
	//	処理ID：	check_ZIP_CODE
	//	概要	：	郵便番号のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:8
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
	const	ZIP_CODE_L_LEN	= 1 ;
	const	ZIP_CODE_U_LEN	= 8 ;
	function	check_ZIP_CODE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 郵便番号チェック
		$ret = FVZipcode::valid($a_out[0], self::ZIP_CODE_L_LEN, self::ZIP_CODE_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "郵便番号", $l_err_info);
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
//			$a_err[] = self::err("郵便番号", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	住所
	//	処理ID：	check_ADDRESS1
	//	概要	：	住所のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:40
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
	const	ADDRESS1_L_LEN	= 1 ;
	const	ADDRESS1_U_LEN	= 40 ;
	function	check_ADDRESS1($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::ADDRESS1_L_LEN, self::ADDRESS1_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "住所", $l_err_info);
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
//		      $a_err[] = self::err("住所", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ビル名等
	//	処理ID：	check_ADDRESS2
	//	概要	：	ビル名等のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:30
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
	const	ADDRESS2_L_LEN	= 1 ;
	const	ADDRESS2_U_LEN	= 30 ;
	function	check_ADDRESS2($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 文字列チェック
		$ret = FVString::validate($a_out[0], self::ADDRESS2_L_LEN, self::ADDRESS2_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ビル名等", $l_err_info);
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
//				$a_err[] = self::err("ビル名等", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	E-Mailアドレス
	//	処理ID：	check_E_MAIL1
	//	概要	：	E-Mailアドレスのチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:128
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
	const	E_MAIL1_L_LEN	= 1 ;
	const	E_MAIL1_U_LEN	= 128 ;
	function	check_E_MAIL1($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// MAIL形式チェック
		$ret = FVEmail::valid($a_out[0], self::E_MAIL1_L_LEN, self::E_MAIL1_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "E-Mailアドレス", $l_err_info);
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
//		      $a_err[] = self::err("E-Mailアドレス", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	Mailアドレス2
	//	処理ID：	check_E_MAIL2
	//	概要	：	Mailアドレス2のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:128
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
	const	E_MAIL2_L_LEN	= 1 ;
	const	E_MAIL2_U_LEN	= 128 ;
	function	check_E_MAIL2($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// MAIL形式チェック
		$ret = FVEmail::valid($a_out[0], self::E_MAIL2_L_LEN, self::E_MAIL2_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "Mailアドレス2", $l_err_info);
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
//		      $a_err[] = self::err("Mailアドレス2", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	Mailアドレス3
	//	処理ID：	check_E_MAIL3
	//	概要	：	Mailアドレス3のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:128
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
	const	E_MAIL3_L_LEN	= 1 ;
	const	E_MAIL3_U_LEN	= 128 ;
	function	check_E_MAIL3($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// MAIL形式チェック
		$ret = FVEmail::valid($a_out[0], self::E_MAIL3_L_LEN, self::E_MAIL3_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "Mailアドレス3", $l_err_info);
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
//		      $a_err[] = self::err("Mailアドレス3", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	Mailアドレス4
	//	処理ID：	check_E_MAIL4
	//	概要	：	Mailアドレス4のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:128
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
	const	E_MAIL4_L_LEN	= 1 ;
	const	E_MAIL4_U_LEN	= 128 ;
	function	check_E_MAIL4($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// MAIL形式チェック
		$ret = FVEmail::valid($a_out[0], self::E_MAIL4_L_LEN, self::E_MAIL4_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "Mailアドレス4", $l_err_info);
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
//		      $a_err[] = self::err("Mailアドレス4", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	Mailアドレス5
	//	処理ID：	check_E_MAIL5
	//	概要	：	Mailアドレス5のチェックをする
	//		型	:半角英数字記号
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:128
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
	const	E_MAIL5_L_LEN	= 1 ;
	const	E_MAIL5_U_LEN	= 128 ;
	function	check_E_MAIL5($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// MAIL形式チェック
		$ret = FVEmail::valid($a_out[0], self::E_MAIL5_L_LEN, self::E_MAIL5_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "Mailアドレス5", $l_err_info);
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
//				$a_err[] = self::err("Mailアドレス5", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	種別
	//	処理ID：	check_KIND
	//	概要	：	種別のチェックをする
	//		型	:数字
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:2
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
	const	KIND_L_LEN	= 1 ;
	const	KIND_U_LEN	= 2 ;
	function	check_KIND($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 既定値チェック
		$ret = FVDefault::valid($a_out[0], self::KIND_L_LEN, self::KIND_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "種別", $l_err_info, $this->m_def_kind);
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
//				$a_err[] = self::err("種別", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	備考
	//	処理ID：	check_NOTE
	//	概要	：	備考のチェックをする
	//		型	:文字列
	//		全半角	:全角
	//		最小桁	:1
	//		最大桁	:40
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
	const	NOTE_L_LEN	= 1 ;
	const	NOTE_U_LEN	= 40 ;
	function	check_NOTE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

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
//				$a_err[] = self::err("備考", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ユーザ情報
	//	処理ID：	check_ID_INFO
	//	概要	：	ユーザ情報のチェックをする
	//		型	:半角英数字
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:16
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
	const	ID_INFO_L_LEN	= 1 ;
	const	ID_INFO_U_LEN	= 16 ;
	function	check_ID_INFO($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 半角英数字チェック
		$ret = FVAlnum::valid($a_out[0], self::ID_INFO_L_LEN, self::ID_INFO_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ユーザ情報", $l_err_info);
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
//				$a_err[] = self::err("ユーザ情報", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ロックフラグ
	//	処理ID：	check_LOCK_FLAG
	//	概要	：	ロックフラグのチェックをする
	//		型	:数字
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:2
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
	const	LOCK_FLAG_L_LEN	= 1 ;
	const	LOCK_FLAG_U_LEN	= 2 ;
	function	check_LOCK_FLAG($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 既定値チェック
		$ret = FVDefault::valid($a_out[0], self::LOCK_FLAG_L_LEN, self::LOCK_FLAG_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ロックフラグ", $l_err_info, $this->m_def_lock_flag);
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
//				$a_err[] = self::err("ロックフラグ", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ロック日時
	//	処理ID：	check_LOCK_TIME
	//	概要	：	ロック日時のチェックをする
	//		型	:日時
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:20
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
	const	LOCK_TIME_L_LEN	= 1 ;
	const	LOCK_TIME_U_LEN	= 20 ;
	function	check_LOCK_TIME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 日付チェック
		$ret = FVDate::valid($a_out[0], self::LOCK_TIME_L_LEN, self::LOCK_TIME_U_LEN, $a_out, FVString::BYTE_MIN_MAX, $l_required, "ロック日時", $l_err_info);
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
//				$a_err[] = self::err("ロック日時", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	//======================================================
	//	処理名：	ロック回数
	//	処理ID：	check_LOCK_CNT
	//	概要	：	ロック回数のチェックをする
	//		型	:数字
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:2
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
	const	LOCK_CNT_L_LEN	= 1 ;
	const	LOCK_CNT_U_LEN	= 2 ;
	function	check_LOCK_CNT($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 数値チェック
		$ret = FVInteger::validate($a_val, self::LOCK_CNT_L_LEN, self::LOCK_CNT_U_LEN, $a_out, FVInteger::DIGITS_MIN_MAX, $l_required, "ロック回数", $l_err_info);
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
//				$a_err[] = self::err("ロック回数", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	//======================================================
	//	処理名：	パスワード更新日
	//	処理ID：	check_PASS_UPD_DATE
	//	概要	：	パスワード更新日のチェックをする
	//		型	:日時
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:20
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
	const	PASS_UPD_DATE_L_LEN	= 1 ;
	const	PASS_UPD_DATE_U_LEN	= 20 ;
	function	check_PASS_UPD_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_required = PVType::REQUIRED_FALSE;
		$l_normalize = self::NMZ_NONE;

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

		// Normalize処理
		if ($l_normalize !== self::NMZ_NONE) {
			$a_out[0] = convertKana($a_val, $l_normalize);
		}

		// 日付チェック
		$ret = FVDate::valid($a_out[0], 
		self::PASS_UPD_DATE_L_LEN, 
		self::PASS_UPD_DATE_U_LEN, 
		$a_out, 
		FVString::BYTE_MIN_MAX, 
		$l_required, 
		"パスワード更新日", 
		$l_err_info);
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
//				$a_err[] = self::err("パスワード更新日", $a_val, PVType::rootWarningMsg . ".data.normalize.had.happened", Reason::WARNIG);
				$ret = 1;
			}
		}

		debug_log("<< ($ret)");
		return $ret;
	}

	// ===========================================================================
	// 関連チェック
	// ===========================================================================

	//======================================================
	// 処理名：	種別とバンクIDのチェック
	// 処理ID：	rel_kind_bankid
	// 概要 ：	種別とバンクIDの入力値チェックを行う。
	// 		種別に管理者(4)が入力されている場合はバンクIDが入力されているとエラーとなる。
	// 		種別にバンク(5)が入力されている場合はバンクIDが必須となる
	//
	// 引数
	//	$a_kind	:種別の入力値
	//	$a_bankid	:バンクIDの入力値
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
	// 戻り値
	//	0	:正常
	//	-1以下	:エラーコード
	//	1	:値を修正して（全角、半角など）正常
	//======================================================
	function	rel_kind_bankid($a_kind,$a_bankid,&$a_out,&$a_err,$a_opt = array()) {
		debug_log(">> ($a_kind, $a_bankid)");

		$l_ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		// 種別がバンクの場合
		if ($a_kind == self::KIND_BANK) {
			// バンクIDの入力チェック
			if (!FVString::required($a_bankid, PVType::REQUIRED, "バンクID", $l_err_info)) {
				$a_err[] = self::err("種別", $a_kind, PVType::rootWarningMsg . ".data.rel.kind.bankid_required", Reason::ERROR);
				$l_ret = -1;
			}
		}
		// 種別が管理者の場合
		else if ($a_kind == self::KIND_ADMIN) {
			// バンクIDが入力されている場合はエラー
			if ($a_bankid !== "") {
				$a_err[] = self::err("種別", $a_kind, PVType::rootWarningMsg . ".data.rel.kind.bankid_input", Reason::ERROR);
				$l_ret = -1;
			}
		}
		return $l_ret;

	}

	//======================================================
	// 処理名：	Userデータチェック
	// 処理ID：	isValid
	// 概要  ：	UserデータdataUserをチェックする
	//
	// 引数
	//	$apd	:データ(apdUser)
	//	$prm	:0(新規),1(更新)
	//		
	// 戻り値
	//	array	:エラーコードのArray
	//======================================================
	function isValid($apd,$prm,&$a_err)
	{
		debug_log(">>()") ;
		debug_log(print_r($apd,True));

		$l_err_info = new ErrorInfo();

		$rtn	= 1;
		$dbd_p	= $apd->getDBDUser();
		// == 単体チェック ==============================================
		$emp = 0;
		if ($prm === 1) {
			$emp = 1;
		}
/*
		if ( self::check_RECID($dbd_p->l_dbo->m_recid,$emp,$l_err_info) !== self::VALID_OK )
		{
			var_dump($l_err_info->errors);
			$rtn = 0;
		}
		// バンクIDのチェック
		if ( self::check_BANKID($dbd_p->l_dbo->m_bankid,1,$l_err_info) !== self::VALID_OK )
		{
			var_dump($l_err_info->errors);
			$rtn = 0;
		}
		// ユーザ名のチェック 
		if ( self::check_USER_NAME($dbd_p->l_dbo->m_user_name,1,$l_err_info) !== self::VALID_OK )
		{
			$rtn = 0;
		}
		// パスワードチェック
		if ( self::check_PASSWD($dbd_p->l_dbo->m_passwd,1,$l_err_info) !== self::VALID_OK )
		{
			$rtn = 0;
		}
*/
		// 機関名称
		// 所属
		// 担当者
		// ふりがな
		// 電話番号
		// 内線番号
		// FAX番号
		// 郵便番号
		// 住所
		// ビル名等
		// E-Mailアドレス
		// Mailアドレス2
		// Mailアドレス3
		// Mailアドレス4
		// Mailアドレス5
		// 種別
		// 備考
		// ユーザ情報
		// ロックフラグ
		// ロック日時
		// ロック回数
		// パスワード更新日

		debug_log("<< $rtn" );
		return $rtn;
	}

	// User 新規データチェック
	function validAdd($apd,&$a_err) {
		return $this->isValid($apd,0,$err);
	}

	// User 更新データチェック
	function validMod($apd,&$a_err) {
		return $this->isValid($apd,1,$err);
	}
} // CLASS-EOF
?>
