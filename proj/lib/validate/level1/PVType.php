<?php
//======================================================
//
// 機能名：	プリミティブ・タイプバリデーションクラス
//
// 機能ID：	PVType.php
// 継承  ：	none
// 概要  ：	L1 validate
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(VALID_DIR ."ErrorInfo.php");
require_once(VALID_DIR ."Cast.php");
require_once(VALID_L1_DIR ."vEmpty.php");
require_once(VALID_L1_DIR ."vUnspecified.php");

class PVType
{
	// デフォ
	const	FLAG_DEFAULT = 0;
	// プリミティブ・タイプバリデーションクラス の番号
	const	TYPE_PV_TYPE = 0;
	// 日付時間(DateTime)型バリデーションクラス の番号
	const	TYPE_FV_DATE_TIME_TYPE = 1;
	// 数値(int)型バリデーションクラス の番号
	const	TYPE_FV_INT_TYPE = 2;
	// 文字列(String)型バリデーションクラス の番号
	const	TYPE_FV_STRING_TYPE = 3;
	// 選択型バリデーションクラス の番号
	const	TYPE_FV_SELECTION_TYPE = 11;
	// メールアドレス型バリデーションクラス の番号
	const	TYPE_FV_MAIL_ADDRESS_TYPE = 21;
	// 郵便番号型バリデーションクラス の番号
	const	TYPE_FV_POSTAL_CODE_TYPE = 22;
	// 電話番号型バリデーションクラス の番号
	const	TYPE_FV_TELEPHONE_NUMBER_TYPE = 23;

	// 関連項目に関するフィールドレベルのバリデータクラス の番号
	const	TYPE_FV_ADDRESS_VTOR_TYPE = 31;

	function __construct()
	{
		parent::__construct();
	}

	// 関数オーバーロード対応
	public static function validate($in, &$out, $flag, $required, $fieldName, $errorInfo) {
		$type	= gettype($out[0]);
		debug_log("input = ".$out[0]);
		debug_log("type = " . $type);

		if ($type === "boolean")	return self::_validate_bool($in, $out, $flag, $required, $fieldName, $errorInfo);
		if ($type === "integer")	return self::_validate_int($in, $out, $flag, $required, $fieldName, $errorInfo);
		if ($type === "float")		return self::_validate_float($in, $out, $flag, $required, $fieldName, $errorInfo);
		if ($type === "double")		return self::_validate_float($in, $out, $flag, $required, $fieldName, $errorInfo);
		if ($type === "string")		return self::_validate_string($in, $out, $flag, $required, $fieldName, $errorInfo);

		return -1;
	}

	//
	// 受け取ったString値を、ブーリアン（Boolean型）に変換する。
	//
	// @param in  確認する文字列
	// @param out 配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return     -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//
	private static function _validate_bool($in, &$out, $flag, $required, $fieldName, $errorInfo) {
		if (!self::required($in, $required, $fieldName, $errorInfo)) { return -1; }

		if ( $required == self::SPECIFIED_AND_REQUIRED ) {
			$required = self::REQUIRED;
		} else if ( $required == self::SPECIFIED_AND_REQUIRED_FALSE ) {
			$required = self::REQUIRED_FALSE;
		} else if ( $required == self::SPECIFIED_AND_REQUIRED_NOT_ALLOW ) {
			$required = self::REQUIRED_NOT_ALLOW;
		}
		if (vUnspecified::is($in)) {
			if($out!=null&&0<count($out)) {
				vUnspecified::set($out[0]);
			}
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		if (vEmpty::is($in)) {
			if($out!=null&&0<count($out)) {
				vEmpty::set($out[0]);
			}
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		$rtn = Cast::toBool($in,$outTemp);
		if($out!=null&&0<count($out)) {
			$out[0] = $outTemp;
		}
		if ($rtn != 0 && $in != null) {
			$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.type.not_bool");
			return -1;
		}
		return 0;
	}

	//
	// 受け取ったString値を、ブーリアン（Boolean型）に変換する。
	//
	// @param in  確認する文字列
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return ブーリアン（Boolean型
	//
	public static function toBool($in, $flag, $required, $fieldName, $errorInfo) {
		$out = array((bool)0,(bool)0);
		self::validate($in, $out, $flag, $required, $fieldName, $errorInfo);
		return $out[0];
	}

	//
	// 受け取ったString値を、文字列（String型）に変換する。
	// <p>↑元々Stringなので、flagで条件を付けなければ常に、0:正常
	//
	// @param in  確認する文字列
	// @param out 配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return     -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//
	private static function _validate_string($in, &$out, $flag, $required, $fieldName, $errorInfo) {
		if (!self::required($in, $required, $fieldName, $errorInfo)) { return -1; }

		if ( $required == self::SPECIFIED_AND_REQUIRED ) {
			$required = self::REQUIRED;
		} else if ( $required == self::SPECIFIED_AND_REQUIRED_FALSE ) {
			$required = self::REQUIRED_FALSE;
		} else if ( $required == self::SPECIFIED_AND_REQUIRED_NOT_ALLOW ) {
			$required = self::REQUIRED_NOT_ALLOW;
		}
		if (vUnspecified::is($in)) {
			if($out!=null&&0<count($out)) {
				vUnspecified::set($out[0]);
			}
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		if (vEmpty::is($in)) {
			if($out!=null&&0<count($out)) {
				vEmpty::set($out[0]);
			}
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		$rtn = Cast::toString($in,$outTemp);
		if($out!=null&&0<count($out)) {
			$out[0] = $outTemp;
		}
		if ($rtn != 0 && $in != null) {
			$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.type.not_string");
			return -1;
		}
		return 0;
	}

	//
	// 受け取ったString値を、文字列（String型）に変換する。
	//
	// @param in  確認する文字列
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return String
	//
	public static function toString($in, $flag, $required, $fieldName, $errorInfo) {
		$out = array((string)null,(string)null);
		self::validate($in, $out, $flag, $required, $fieldName, $errorInfo);
		return $out[0];
	}

	//
	// 受け取ったString値を、数値（int型）に変換する。
	//
	// @param in  確認する文字列
	// @param out 配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return     -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	///
	private static function _validate_int($in, &$out, $flag, $required, $fieldName, $errorInfo) {
		debug_log("<< ($in,$required,$fieldName)");

		if (!self::required($in, $required, $fieldName, $errorInfo)) { return -1; }

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
		$rtn = Cast::toInt($in,$outTemp);
		if($out!=null&&0<count($out)) {
			$out[0] = $outTemp;
		}
		if ($rtn != 0 && $in != null) {
			$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.type.not_int");
			return -1;
		}
		return 0;
	}

	//
	// 受け取ったString値を、文字列（int型）に変換する。
	//
	// @param in  確認する文字列
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return String
	//
	public static function toInt($in, $flag, $required, $fieldName, $errorInfo) {
		$out = array((int)null,(int)null);
		self::validate($in, $out, $flag, $required, $fieldName, $errorInfo);
		return $out[0];
	}

	//
	// 受け取ったString値を、数値（float型）に変換する。※floatとdoubleは同じ扱い
	//
	// @param in  確認する文字列
	// @param out 配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return     -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	///
	private static function _validate_float($in, &$out, $flag, $required, $fieldName, $errorInfo) {
		if (!self::required($in, $required, $fieldName, $errorInfo)) { return -1; }

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
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		if (vEmpty::is($in)) {
			if($out!=null&&0<count($out)) {
				vEmpty::set($out[0]);
			}
			if ($required==REQUIRED_FALSE || $required==REQUIRED_NOT_ALLOW) {return 0;}  // OK!
		}
		$rtn = Cast::toFloat($in,$outTemp);
		if($out!=null&&0<count($out)) {
			$out[0] = $outTemp;
		}
		if ($rtn != 0 && $in != null) {
			$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.type.not_float");
			return -1;
		}
		return 0;
	}

	//
	// 受け取ったString値を、文字列（float型）に変換する。※floatとdoubleは同じ扱い
	//
	// @param in  確認する文字列
	// @param flag    未使用
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return String
	//
	public static function toFloat($in, $flag, $required, $fieldName, $errorInfo) {
		$out = array((float)null,(float)null);
		self::validate($in, $out, $flag, $required, $fieldName, $errorInfo);
		return $out[0];
	}

	/** 入力不可 */
	const	REQUIRED_NOT_ALLOW = -1;
	/** 任意(全てＯＫ！) */
	const	REQUIRED_FALSE = 0;
	/** 値　必須 */
	const	REQUIRED = 1;
	/** 受信必須 & 入力不可 */
	const	SPECIFIED_AND_REQUIRED_NOT_ALLOW = -11;
	/** 受信必須 & 値任意 */
	const	SPECIFIED_AND_REQUIRED_FALSE = 10;
	/** 受信　値　必須 */
	const	SPECIFIED_AND_REQUIRED = 11;
	/** 非更新項目  */
	const	NOT_UPDATE = 20;

	//
	// 必須確認
	//
	// @param in  確認する文字列
	// @param required    -1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return     false:エラー、true:正常
	//
	private static function required($in, $required, $fieldName, $errorInfo) {
		switch($required) {
			case self::REQUIRED_NOT_ALLOW: // -1:入力不可
				if (vUnspecified::is($in)) {
					// OK!
				} else if (vEmpty::is($in)) {
					// OK!
				} else {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.not.arrow");
					return false;
				}
				break;
			case self::REQUIRED_FALSE: // 0:任意（何でもOK!
				// OK!
				break;
			case self::REQUIRED:    // 1:必須
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.mandatory");
					return false;
				} else if (vEmpty::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.mandatory");
					return false;
				} else {
					// OK!
				}
				break;
			case self::SPECIFIED_AND_REQUIRED_NOT_ALLOW: // -11:受信必須 　値入力不可
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.not_received");
					return false;
				} else if (vEmpty::is($in)) {
					// OK!
				} else {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.not.arrow");
					return false;
				}
				break;
			case self::SPECIFIED_AND_REQUIRED_FALSE: // 10:受信必須　値任意（何でもOK!
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.not_received");
					return false;
				}
				break;
			case self::SPECIFIED_AND_REQUIRED:    // 11:受信　値　必須
				if (vUnspecified::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.not_received");
					return false;
				} else if (vEmpty::is($in)) {
					$errorInfo->addError($fieldName, $in, self::rootErrorMsg . ".data.mandatory");
					return false;
				} else {
					// OK!
				}
			default:
		}
		return true;
	}

	//
	// バリデーション時におけるエラーメッセージの基底部分を返す。
	//
	// @return エラーメッセージの基底文字列
	//
	const rootErrorMsg	= "error.validation" ;

	//
	// バリデーション時におけるワーニングメッセージの基底部分を返す。
	//
	// @return ワーニングメッセージの基底文字列
	//
	const	rootWarningMsg	= "warning.validation";
	
} // CLASS-EOF
?>
