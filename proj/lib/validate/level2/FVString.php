<?php
//======================================================
//
// 機能名：	項目・文字列(String)型バリデーション
//
// 機能ID：	FVString.php
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
require_once(VALID_DIR ."ErrorInfo.php");

class FVString
{
	//
	// 【デフォルト】 バイト数 UTF-8換算
	// <p>（arg1:最小バイト数, arg2:最大バイト数）</p>
	//
	const	BYTE_MIN_MAX = 0;
	//
	// 【デフォルト】 バイト数 UTF-8換算
	// <p>（arg1:最小バイト数, arg2:最大バイト数）</p>
	//
	const	BYTE_UTF8_MIN_MAX = 0;
	const	BYTE_UTF8_CHARSET_NAME = "UTF-8";
	//
	// バイト数 Shift_JIS換算
	// <p>（arg1:最小バイト数, arg2:最大バイト数）</p>
	//
	const	BYTE_SHIFT_JIS_MIN_MAX = 1;
	const	BYTE_SHIFT_JIS_CHARSET_NAME = "Shift_JIS";
	//
	// バイト数 euc-jp換算
	// <p>（arg1:最小バイト数, arg2:最大バイト数）</p>
	//
	const	BYTE_EUC_MIN_MAX = 2;
	const	BYTE_EUC_CHARSET_NAME = "EUC-JP";
	//
	// 文字数
	// <p>（arg1最小文字数:, arg2:最大文字数）</p>
	//
	const	LETTERS_MIN_MAX = 3;
	//
	// UTF8で1バイトの文字は1、それ以外は、何バイトでも2として数える。
	// <p>（arg1:最小数, arg2:最大数）</p>
	//
	const	BYTE_UTF8_ONE_AS_ONE_ELSE_TWO_MIN_MAX = 4;
	//
	// 空白を削除した後に、UTF8で1バイトの文字は1、それ以外は、何バイトでも2として数える。
	// <p>（arg1:最小数, arg2:最大数）</p>
	//
	const	BYTE_UTF8_ONE_AS_ONE_ELSE_TWO_WITHOUT_SPACE_MIN_MAX = 5;
	//
	// ASCII文字のみを、文字数を数える。ASCII文字以外が含まれる場合は、エラー。
	// <p>（arg1最小文字数:, arg2:最大文字数）</p>
	//
	const	LETTERS_ASCII = 6;
	const	LETTERS_ASCII_RE = "^[\\p{Alnum}\\p{Punct}\\p{Blank}]*$"; //表示できる文字、空白またはタブ
	//
	// ASCII文字と全角のASCII文字置き換え可能な文字のみ置き換えて、文字数を数える。ASCIIに置き換えられないものは、エラー。
	// <p>（arg1最小文字数:, arg2:最大文字数）</p>
	//
	const	LETTERS_ASCII_WITH_REPLACE = 7;
	//
	// UTF8で1バイトの文字は1/2文字、それ以外は、何バイトでも1文字として数える。
	// <p>（arg1:最小文字数, arg2:最大文字数）</p>
	//
	const	LETTERS_UTF8_ONE_AS_1_OVER_2_ELSE_ONE_MIN_MAX = 8;
	//
	// 空白を削除した後に、UTF8で1バイトの文字は1/2文字、それ以外は、何バイトでも1文字として数える。
	// <p>（arg1:最小文字数, arg2:最大文字数）</p>
	//
	const	LETTERS_UTF8_ONE_AS_1_OVER_2_ELSE_ONE_WITHOUT_SPACE_MIN_MAX = 9;

	//
	// 受け取ったString値が、 arg1～arg2の範囲内のバイト数、または、文字数に収まるのかを確認する。
	//
	// <p>
	// flag
	//	0:バイト数 UTF-8換算（arg1:最小バイト数, arg2:最大バイト数）
	//	1:バイト数 Shift-JIS換算（arg1:最小バイト数, arg2:最大バイト数）
	//	2:バイト数 euc-jp（arg1:最小バイト数, arg2:最大バイト数）
	//	3:文字数（arg1最小文字数:, arg2:最大文字数）
	//	4:角1全角2として文字数を数える（arg1最小数:, arg2:最大数）
	//	5:空白を除いた後に、角1全角2として文字数を数える（arg1最小数:, arg2:最大数）
	//	6:ASCII文字のみを、文字数を数える。ASCII文字以外が含まれる場合は、エラー。（arg1最小文字数:, arg2:最大文字数）
	//	7:ASCII文字と全角のASCII文字置き換え可能な文字のみ置き換えて、文字数を数える。ASCIIに置き換えられないものは、エラー。（arg1最小文字数:, arg2:最大文字数）
	// </p>
	// ※ デフォルトは、バイト数の、Charset:UTF-8です。
	// ※ シフトJISとEUCの1バイトコード（半角文字）
	//		0x00～0x1f、0x7f は制御コードです
	//		0x20～0x7e はASCII文字です
	//	シフトJISの1バイトコード（半角文字）
	//		0xa1～0xdf は半角カタカナです
	//	上記以外は、2バイトコード（全角文字）です。
	//
	//	EUCの半角カタカナのエリアは2バイトコードで別にエリアに設けています
	//	　 上位1バイト　 0x8e
	//	　 下位1バイト　 0xa1～0xdf
	//
	// @param in	確認する文字列
	// @param arg1  最小バイト、または、文字数
	// @param arg2  最大バイト、または、文字数
	// @param out   配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param flag  0:バイト(UTF-8)数、1バイト(Shift_JIS)数、2:バイト(euc-jp)数、3:文字数、4:半角1全角2文字数、5:半角1全角2文字数(空白を除く)
	// @param required  -1:入力不可、0:任意、1:必須
	// @param fieldName 確認項目名
	// @param errorInfo エラー情報
	// @return  -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//
	//private static function validate(String in, Long arg1, Long arg2, String[] out, int flag, int required, String fieldName, ErrorInfo errorInfo) {
	public static function validate($in, $arg1, $arg2, &$out, $flag, $required, $fieldName, $errorInfo) {
		if($out!=null&&0<count($out)) {
			if ( $required == PVType::NOT_UPDATE ) {
				vUnspecified::set($out[0]);
				return 0;
			} else {
				$out[0] = $in;
			}
		}
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
		// 1:バイト数（arg1:最小バイト数, arg2:最大バイト数）Shift_JIS換算
		else if ($flag == self::BYTE_SHIFT_JIS_MIN_MAX) {
			return self::bytes($in, $arg1, $arg2, self::BYTE_SHIFT_JIS_CHARSET_NAME, $out, $fieldName, $errorInfo);
		}
		// 2:バイト数（arg1:最小バイト数, arg2:最大バイト数）euc-jp換算
		else if ($flag == self::BYTE_EUC_MIN_MAX) {
			return self::bytes($in, $arg1, $arg2, self::BYTE_EUC_CHARSET_NAME, $out, $fieldName, $errorInfo);
		}
		// 3:文字数（arg1最小文字数:, arg2:最大文字数）
		if ($flag == self::LETTERS_MIN_MAX) {
			$in_length = mb_strwidth($in);
			if (arg1 == arg2 && in_length != arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.letters", $arg1);
				return -1;
			}
			else if (in_length < arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.letters", $arg1);
				return -1;
			}
			else if (arg2 < in_length) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max.letters", $arg2);
				return -1;
			}
		}
		// 4:コードセットUTF8で1バイトの文字は1、それ以外は、何バイトでも2として数える。（arg1:最小数, arg2:最大数）
		else if ($flag == self::BYTE_UTF8_ONE_AS_ONE_ELSE_TWO_MIN_MAX) {
			return self::oneOrTwo($in, $arg1, $arg2, self::BYTE_UTF8_CHARSET_NAME, $out, $fieldName, $errorInfo);
		}
		// 5:空白を削除した後に、コードセットUTF8で1バイトの文字は1、それ以外は、何バイトでも2として数える。（arg1:最小数, arg2:最大数）
		else if ($flag == self::BYTE_UTF8_ONE_AS_ONE_ELSE_TWO_WITHOUT_SPACE_MIN_MAX) {

			$didNormalize = 0;

			$inTMP = $in.replaceAll("\\s","");
			$inTMP = $inTMP.replaceAll("　","");

			if ($inTMP!=$in) {
				$errorInfo->addWarning($fieldName, $in, PVType::rootWarningMsg . ".data.normalize.had.happened",$in,$inTMP);
				$in = $inTMP;
				$didNormalize=1;
			}

			$returnTMP = self::oneOrTwo($in, $arg1, $arg2, self::BYTE_UTF8_CHARSET_NAME, $out, $fieldName, $errorInfo);

			return $returnTMP < 0 ? $returnTMP : $didNormalize;
		}
		// 6:ASCII文字のみを、文字数を数える。ASCII文字以外が含まれる場合は、エラー。（arg1最小文字数:, arg2:最大文字数）
		// 7:ASCII文字と全角のASCII文字置き換え可能な文字のみ置き換えて、文字数を数える。ASCIIに置き換えられないものは、エラー。（arg1最小文字数:, arg2:最大文字数）
		else if ($flag == self::LETTERS_ASCII || $flag == self::LETTERS_ASCII_WITH_REPLACE) {

			$didNormalize = 0;
			if ($flag == self::LETTERS_ASCII_WITH_REPLACE) {

				$inTMP = $in;	//Normalizer.normalize(in, Normalizer.Form.NFKC);

				$inTMP = $inTMP.replaceAll("[－‐―ー]", "-");
				$inTMP = $inTMP.replaceAll("[’]", "'");
				$inTMP = $inTMP.replaceAll("[‘]", "`");
				while(-1<$inTMP.indexOf("\\")) {
					$inTMP = $inTMP.substring(0,$inTMP.indexOf("\\"))+"\\".$inTMP.substring($inTMP.indexOf("\\")+1);
				}

				//System.out.println("in="+in+" out="+inTMP);

				if ($inTMP!=$in) {
					$errorInfo->addWarning($fieldName, $in, PVType::rootWarningMsg . ".data.normalize.had.happened",$in,$inTMP);
					$in = $inTMP;
					$didNormalize=1;
				}

			}

			$out[0] = self::LETTERS_ASCII_RE;
			$returnTMP = PVType::validate($in, $out, PVType::REGULAR_EXPRESSION_MUST_MATCH, $required, $fieldName, $errorInfo);

			$in_length = $in.toCharArray().length;
			if ($arg1 == $arg2 && $in_length != $arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.letters", $arg1);
				return -1;
			}
			else if ($in_length < $arg1) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.letters", $arg1);
				return -1;
			}
			else if (arg2 < in_length) {
				$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max.letters", $arg2);
				return -1;
			}

			return $returnTMP < 0 ? $returnTMP : $didNormalize;
		}
		// 8:コードセットUTF8で1バイトの文字は1/2文字、それ以外は、何バイトでも1文字として数える。（arg1:最小文字数, arg2:最大文字数）
		else if ($flag == self::LETTERS_UTF8_ONE_AS_1_OVER_2_ELSE_ONE_MIN_MAX) {
			return self::oneOverTwoOrOne($in, $arg1, $arg2, self::BYTE_UTF8_CHARSET_NAME, $out, $fieldName, $errorInfo);
		}
		// 9:空白を削除した後に、コードセットUTF8で1バイトの文字は1/2文字、それ以外は、何バイトでも1文字として数える。（arg1:最小文字数, arg2:最大文字数）
		else if ($flag == self::LETTERS_UTF8_ONE_AS_1_OVER_2_ELSE_ONE_WITHOUT_SPACE_MIN_MAX) {

			$didNormalize = 0;

			$inTMP = $in.replaceAll("\\s","");
			$inTMP = $inTMP.replaceAll("　","");

			if ($inTMP!=in) {
				$errorInfo->addWarning($fieldName, $in, PVType::rootWarningMsg . ".data.normalize.had.happened",$in,$inTMP);
				$in = $inTMP;
				$didNormalize=1;
			}

			$returnTMP = self::oneOverTwoOrOne($in, $arg1, $arg2, self::BYTE_UTF8_CHARSET_NAME, $out, $fieldName, $errorInfo);

			return $returnTMP < 0 ? $returnTMP : $didNormalize;
		}
		// 【デフォルト】 0:バイト数（arg1:最小バイト数, arg2:最大バイト数）UTF-8換算
		else {
			return self::bytes($in, $arg1, $arg2, self::BYTE_UTF8_CHARSET_NAME, $out, $fieldName, $errorInfo);
		}
		return 0;
	}
	//private static int oneOverTwoOrOne(String in, Long arg1, Long arg2, String arg3, String[] out, String fieldName, ErrorInfo errorInfo) {
	private static function oneOverTwoOrOne($in, $arg1, $arg2, $arg3, &$out, $fieldName, $errorInfo) {
		$arg1TimesTwo = arg1!=null?($arg1*2):0;
		if (0<$arg1TimesTwo) { $arg1TimesTwo = $arg1TimesTwo-1; }
		$arg2TimesTwo = $arg2!=null?($arg2*2):0;
		return oneOrTwo($in, $arg1TimesTwo, $arg2TimesTwo, $arg3, $out, $fieldName, $errorInfo);
	}
	//private static int oneOrTwo(String in, Long arg1, Long arg2, String arg3, String[] out, String fieldName, ErrorInfo errorInfo) {
	private static function oneOrTwo($in, $arg1, $arg2, $arg3, &$out, $fieldName, $errorInfo) {
		if (!mb_internal_encoding($arg3)) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".charset.not.supported", $arg3);
			return -1;
		}
		$cs = mb_internal_encoding();
		$letterOneOrTwoCount = 0;
		$length = mb_strlen($in);
		debug_log("length = " . $length);
		for ($i=0;$i<$length;$i++) {

			if (1==mb_strwidth(substr($in,$i,1))) {$letterOneOrTwoCount+=1;}
			else if (1<mb_strwidth(substr($in,$i,1))) {$letterOneOrTwoCount+=2;}
		}
		if ($arg1 == $arg2 && $letterOneOrTwoCount != $arg1) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.byte.one_or_two", (string)($arg1/2));
			return -1;
		}
		else if ($letterOneOrTwoCount < $arg1) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.byte.one_or_two", (string)($arg1/2));
			return -1;
		}
		else if ($arg2 < $letterOneOrTwoCount) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max.byte.one_or_two", (string)($arg2/2));
			return -1;
		}

		if($out!=null&&0<count($out)) {
			$out[0] = $in;
		}
		return 0;
	}

	//
	// @param in	確認する文字列
	// @param arg1  最小バイト
	// @param arg2  最大バイト
	// @param arg3  Charset名前
	// @param out   配列の1番目に修正後の値を入れる（配列自体がnull or .length<1なら何もしない）
	// @param fieldName 確認項目名
	// @param errorInfo エラー情報
	// @return  -1:エラー有り、0:正常、1:値を修正して（全角、半角など）正常
	//
	//private static int bytes(String in, Long arg1, Long arg2, String arg3, String[] out, String fieldName, ErrorInfo errorInfo) {
	private static function bytes($in, $arg1, $arg2, $arg3, &$out, $fieldName, $errorInfo) {
		if (!mb_internal_encoding($arg3)) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".charset.not.supported", $arg3);
			return -1;
		}
		$in_bytes = mb_strwidth($in);
		debug_log("in byte = " . $in_bytes);
		if ($arg1 == $arg2 && $in_bytes != $arg1) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.byte", $arg1);
			return -1;
		}
		else if ($in_bytes < $arg1) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.min.byte", $arg1);
			return -1;
		}
		else if ($arg2 < $in_bytes) {
			$errorInfo->addError($fieldName, $in, PVType::rootErrorMsg . ".data.max.byte", $arg2);
			return -1;
		}

		if($out!=null&&0<count($out)) {
			$out[0] = $in;
		}
		return 0;
	}

	//
	// 必須確認
	//
	// @param in  確認するString値
	// @param required	-1:入力不可、0:任意、1:必須
	// @param fieldName   確認項目名
	// @param errorInfo   エラー情報
	// @return	 false:エラー、true:正常
	//
	//private static boolean required(String in, int required, String fieldName, ErrorInfo errorInfo) {
	public static function required($in, $required, $fieldName, $errorInfo) {
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
