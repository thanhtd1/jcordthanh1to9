<?php
//======================================================
//
// 機能名：	項目・数値(Integer)型バリデーション
//
// 機能ID：	FVInteger.php
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
require_once(VALID_L1_DIR . "PVType.php");
require_once(VALID_L1_DIR . "vEmpty.php");
require_once(VALID_L1_DIR . "vUnspecified.php");
require_once(VALID_DIR . "Valid.php");
require_once(VALID_DIR . "ErrorInfo.php");

class FVInteger
{
	// flag	0:範囲（arg1:最小値, arg2:最大値）、1:桁数（arg1最小桁:, arg2:最大桁）、2：桁数で0をarg1でチェックする。arg1=0なら、in=0は許可 （arg1最小桁:, arg2:最大桁）
	const	MIN_MAX = 0;
	const	DIGITS_MIN_MAX = 1;
	const	DIGITS_MIN_MAX_ZERO_CHECK = 2;

	//
	// 受け取ったLong値が、 arg1～arg2の範囲内の数値、または、桁数内に収まるのかを確認する。
	//
	// @param in	確認する数値
	// @param arg1  比較する数値（最小側）
	// @param arg2  比較する数値（最大側）
	// @param out   配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag  0:範囲（arg1:最小値, arg2:最大値）、1:桁数（arg1最小桁:, arg2:最大桁）
	// @param required  -1:入力不可、0:任意、1:必須
	// @param fieldName 確認項目名
	// @param errorInfo エラー情報
	// @return	 -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//
	//protected static int validate(Long in, Long arg1, Long arg2, Long[] out , int flag, int required,  String fieldName, ErrorInfo errorInfo) {
	public static function validate($in, $arg1, $arg2, &$out , $flag, $required, $fieldName, &$errorInfo) {
		if ($out!=null&&0<count($out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($out[0]);
				return 0;
			} else {
				$out[0] = $in;
			}
		}

		if (!self::required($in, $required, $fieldName, $errorInfo)) { return -1; }

		$l_out = array();
		$l_out[0] = Valid::TYPE_INTEGER;
		$ret = PVType::validate($in, $l_out, PVType::FLAG_DEFAULT, $required, $fieldName, $errorInfo);
		if ($ret < 0) {
			return $ret;
		}

		if ( $required == PVType::SPECIFIED_AND_REQUIRED ) {
			$required = PVType::REQUIRED;
		} else if ( $required == PVType::SPECIFIED_AND_REQUIRED_FALSE ) {
			$required = PVType::REQUIRED_FALSE;
		} else if ( $required == PVType::SPECIFIED_AND_REQUIRED_NOT_ALLOW ) {
			$required = PVType::REQUIRED_NOT_ALLOW;
		}
		if (vUnspecified::is($in)) {
			if($out!=null&&0<count($out)) {
				vUnspecified::set($out[0]);
			}
			if ($required==PVType::REQUIRED_FALSE || $required==PVType::REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		if (vEmpty::is($in)) {
			if($out!=null&&0<count($out)) {
				vEmpty::set($out[0]);
			}
			if ($required==PVType::REQUIRED_FALSE || $required==PVType::REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		// 1:桁数（arg1最小桁:, arg2:最大桁）|| 2：桁数で0をarg1でチェックする。arg1=0なら、in=0は許可 （arg1最小桁:, arg2:最大桁）
		if ($flag == self::DIGITS_MIN_MAX || $flag == self::DIGITS_MIN_MAX_ZERO_CHECK) {
			if ($in < 0) { // 桁数の確認では、マイナス値は、問答無用でエラーとします。
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.negative.digits", $arg1);
				return -1;
			}
			else if ($flag == self::DIGITS_MIN_MAX_ZERO_CHECK &&
					$in==0 && $arg1!=0) { // arg1=0 の時は、in=0を許可。それ以外で、in=0は、不可!
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.digits", $arg1);
				return -1;
			}
			else if ($arg1 == $arg2 && mb_strwidth($in) != arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.digits", $arg1);
				return -1;
			}
			else if (mb_strwidth($in) < $arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.digits", $arg1);
				return -1;
			}
			else if ($arg2 < mb_strwidth($in)) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max.digits", $arg2);
				return -1;
			}
		}

		// 【デフォルト】 0:範囲（arg1:最小値, arg2:最大値）
		else {
			if (($in - $arg1) < 0) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min", $arg1);
				return -1;
			}
			else if (0 < ($in - $arg2)) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max", $arg2);
				return -1;
			}
		}
		return 0;
	}

	//
	// 必須確認
	//
	// @param in  確認するLong値
	// @param required	-1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return	 false:エラー、true:正常
	//	 
	//protected static boolean required(Long in, int required, String fieldName, ErrorInfo errorInfo) {
	private static function required($in, $required, $fieldName, &$errorInfo) {
		switch($required) {
			case PVType::REQUIRED_NOT_ALLOW: // -1:入力不可
				if (vUnspecified::is($in)) {
					// OK!
				} else if (vEmpty::is($in)) {
					// OK!
				} else {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.not.arrow");
					return false;
				}
				break;
			case PVType::REQUIRED_FALSE: // 0:任意（何でもOK!
				// OK!
				break;
			case PVType::REQUIRED:	// 1:必須
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.mandatory");
					return false;
				} else if (vEmpty::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.mandatory");
					return false;
				} else {
					// OK!
				}
				break;
			case PVType::SPECIFIED_AND_REQUIRED_NOT_ALLOW: // -11:受信必須 　値入力不可
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.not_received");
					return false;
				} else if (vEmpty::is($in)) {
					// OK!
				} else {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.not.arrow");
					return false;
				}
				break;
			case PVType::SPECIFIED_AND_REQUIRED_FALSE: // 10:受信必須　値任意（何でもOK!
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.not_received");
					return false;
				}
				break;
			case PVType::SPECIFIED_AND_REQUIRED:	// 11:受信　値　必須
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.not_received");
					return false;
				} else if (vEmpty::is($in)) {
					$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.mandatory");
					return false;
				} else {
					// OK!
				}
			default:
		}
		return true;
	}
} // CLASS-EOF
?>
