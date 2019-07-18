<?php
//======================================================
//
// 機能名：	項目・文字列(String)型半角英数字バリデーション
//
// 機能ID：	FVAlnum.php
// 継承  ：	none
// 概要  ：	L2 validate
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(VALID_L1_DIR ."PVType.php");
require_once(VALID_L1_DIR ."vEmpty.php");
require_once(VALID_L1_DIR ."vUnspecified.php");
require_once(VALID_L2_DIR ."FVString.php");
require_once(VALID_DIR ."ErrorInfo.php");

class FVDate extends FVString
{
	// @param a_in    確認する文字列
	// @param a_arg1  最小バイト、または、文字数
	// @param a_arg2  最大バイト、または、文字数
	// @param a_out   配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param a_flag  0:バイト(UTF-8)数、1バイト(Shift_JIS)数、2:バイト(euc-jp)数、3:文字数、4:半角1全角2文字数、5:半角1全角2文字数(空白を除く)
	// @param a_required  -1:入力不可、0:任意、1:必須
	// @param a_fieldName 確認項目名
	// @param a_errorInfo エラー情報
	// @return  -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//	
	public static function valid($a_in, $a_arg1, $a_arg2, &$a_out, $a_flag, $a_required, $a_fieldName, $a_errorInfo)
	{
		debug_log(">> ($a_in, $a_required)");
		$l_ret = 0;

		// 必須チェック
		if (!self::required($a_in, $a_required, $a_fieldName, $a_errorInfo)) { return -1; }

		if ( $a_required == PVType::SPECIFIED_AND_REQUIRED ) {
			$a_required = PVType::REQUIRED;
		} else if ( $a_required == PVType::SPECIFIED_AND_REQUIRED_FALSE ) {
			$a_required = PVType::REQUIRED_FALSE;
		} else if ( $a_required == PVType::SPECIFIED_AND_REQUIRED_NOT_ALLOW ) {
			$a_required = PVType::REQUIRED_NOT_ALLOW;
		}
		if (vUnspecified::is($a_in)) {
			if($a_out!=null&&0<count($a_out)) {
				vUnspecified::set($a_out[0]);
			}
			if ($a_required==PVType::REQUIRED_FALSE || $a_required==PVType::REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		if (vEmpty::is($a_in)) {
			if($a_out!=null&&0<count($a_out)) {
				vEmpty::set($a_out[0]);
			}
			if ($a_required==PVType::REQUIRED_FALSE || $a_required==PVType::REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}

		// 郵便番号のチェック(ハイフンなし)
		try {
			$l_date = new DateTime($a_in);

			debug_log("date = " . $l_date->format("Y/m/d H:i:s"));
		}
		catch (Exception $e) {
			$a_errorInfo->addError($a_fieldName, $a_in, $e->getMessage());
			return -1;
		}

		// 文字列チェック
		$l_ret = self::validate($a_in, $a_arg1, $a_arg2, $a_out, $a_flag, $a_required, $a_fieldName, $a_errorInfo);
		return $l_ret;
	}
} // CLASS-EOF
?>
