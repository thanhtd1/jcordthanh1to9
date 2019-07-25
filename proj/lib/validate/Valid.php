<?php

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

class Valid
{
	// common return code
	const	VALID_OK		= 0;	// 正常
	const	VALID_NULL_ERR		= 1;	// 必須エラー
	const	VALID_NUMERIC_ERR	= 2;	// 数値エラー
	const	VALID_HANKAKU_ERR	= 3;	// 半角エラー
	const	VALID_LENGTH_ERR	= 4;	// 長さエラー
	const	VALID_RANGE_ERR		= 5;	// 範囲エラー
	const	VALID_INVALID_ERR	= 6;	// 有効エラー
	const	VALID_DATE_ERR		= 7;	// 日付エラー
	const	VALID_TIME_ERR		= 8;	// 時刻エラー
	const	VALID_NOT_NULL_ERR	= 9;	// 不要エラー
	const	VALID_EQUALS_ERR	= 10;	// 同値エラー
	const	VALID_SELECT_ERR	= 11;	// 指定エラー
	const	VALID_FORMAT_ERR	= 12;	// 書式エラー
	const	VALID_NUMBER_ERR	= 13;	// 数字エラー
	const	VALID_DEFAULT_ERR	= 14;	// 既定値エラー

	const	VALID_LEVE_ERR		= "ERROR"; // Level ERROR
	const	VALID_LEVE_WAR		= "WARNING"; // Level WARNING

	const	TYPE_INTEGER		= 1;	// 数値型
	const	TYPE_STRING		= "a";	// 文字列型

	const	NMZ_NONE		= "";		// ノーマライズしない
	const	NMZ_INT_HAN		= "n";		// 数字を半角数字へノーマライズする
	const	NMZ_INT_ZEN		= "N";		// 数字を全角数字へノーマライズする
	const	NMZ_ALPHA_HAN		= "r";		// 英字を半角英字へノーマライズする
	const	NMZ_ALPHA_ZEN		= "R";		// 英字を全角英字へノーマライズする
	const	NMZ_ALPHA_INT_KIGOU_HAN	= "aqbtswp";	// 英数字記号を半角英数字記号へノーマライズする
	const	NMZ_ALPHA_INT_KIGOU_ZEN	= "AQBTSWP";	// 英数字記号を全角英数字記号へノーマライズする
	const	NMZ_HAN_KANA_ZEN_KANA	= "k";		// 半角カナを全角カナへノーマライズする
	const	NMZ_ZEN_KANA_HAN_KANA	= "K";		// 全角カナを半角カナへノーマライズする
	const	NMZ_HAN_KANA_ZEN_HIRA	= "h";		// 半角カナを全角ひらがなへノーマライズする
	const	NMZ_ZEN_HIRA_HAN_KANA	= "H";		// 全角ひらがなを半角カナへノーマライズする
	const	NMZ_ZEN_KANA_ZEN_HIRA	= "c";		// 全角カナを全角ひらがなへノーマライズする
	const	NMZ_ZEN_HIRA_ZEN_KANA	= "C";		// 全角ひらがなを全角カナへノーマライズする

	function __construct()
	{
	}

	function err($name,$how,$why,$level)
	{
		$err = array( "what" => $name, "how" => $how, "why" => $why, "level" => $level );

		return $err;
	}
} // CLASS-EOF
?>
