<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
//======================================================
//
// 機能名：     Util date lib.
//
// 機能ID：     util_date.php
// 継承  ：     none
// 概要  ：     common util api.
//
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

define('NENDAI_GENGO',		2018);
define('NENDAI_HEISEI',		1988);
define('NENDAI_SYOWA',		1925);
define('NENDAI_TAISHYOU',	1911);
define('NENDAI_MEIJI',		1867);

define("MEIJI", 1868);
define("TAISHYOU", 1912);
define("SYOUWA", 1926);
define("HEISEI", 1989);
define("GENGO", 2019);

define("DATE_TIME_FORMAT",	"YmdHis");
define("DATE_TIME_FORMAT2",	"Y/m/d H:i:s");
define("DATE_TIME_FORMAT3",	"Y-m-d H:i:s");
define("DATE_TIME_FORMAT4",	"Y年m月d日 H時i分s秒");

define("DATE_TIME_KIND",	1);
define("DATE_TIME_KIND2",	2);
define("DATE_TIME_KIND3",	3);
define("DATE_TIME_KIND4",	4);

// カレントの現在日時に指定フォーマットの日付文字列を作成
function getCurrentDateTime($kind,$md=0) {
	$format = DATE_TIME_FORMAT;
	if ($kind === DATE_TIME_KIND) {
		$format = DATE_TIME_FORMAT;
	}
	elseif ($kind === DATE_TIME_KIND2) {
		$format = DATE_TIME_FORMAT2;
	}
	elseif ($kind === DATE_TIME_KIND3) {
		$format = DATE_TIME_FORMAT3;
	}
	elseif ($kind === DATE_TIME_KIND4) {
		$format = DATE_TIME_FORMAT4;
	}

	$date = new DateTime('now');
	if ( $md < 0 )
	{
		$date->modify('-' . $md . ' days');
	}
	else if ( $md > 0 )
	{
		$date->modify('+' . $md . ' days');
	}
	return $date->format($format);
}

function dropDate($str,$mode=0)
{
	//=========================================
	// mode=0 in[YYYY-MM-DD ...] out[YYYY-MM-DD]
	// mode=1 in[YYYYMMDD ...]   out[YYYYMMDD]
	//=========================================
	if ( $mode == 1 )
	{
		return substr($str,0,8);
	}
	return substr($str,0,10);
}

function convertFormat($str,$mode=0)
{
	//=========================================
	// mode=0 in[YYYY-MM-DD...] out[YYYYMMDD]
	// mode=1 in[YYYYMMDD...]   out[YYYY-MM-DD]
	//=========================================
	if ( $mode == 1 )
	{
		if( strlen($str) != 8 )
		{
			return $str;
		}

		$str	= dropDate($str,1);
		return substr($str,0,4) . "-" . substr($str,4,2) . "-" . substr($str,6,2);
	}

	$str	= dropDate($str,0);
	$item	= explode('-', $str);
	if ( count($item) != 3 )
	{
		return '';
	}
	return $item[0] . $item[1] .$item[2] ;
}

//======================================================
// 処理名：和暦西暦変換
// 処理ID：
// 概要  ：4:令和,3:平成,2:昭和,1:大正,0:明治
//
// 引数
		
// 戻り値
//	YYYYMMDD
//======================================================
function warekiToDate($g,$y,$m=0,$d=0)
{
	if ( is_numeric($g) == FALSE || is_numeric($y) == FALSE || is_numeric($m) == FALSE || is_numeric($d) == FALSE )
	{
		return "";
	}

	//=========================================
	// 和暦（年代＋年＋月＋日）→西暦
	//=========================================
	if ( $g == 4 ) //令和
	{
		$byear = (int)$y + NENDAI_GENGO;
	}
	else if ( $g == 3 ) //平成
	{
		$byear = (int)$y + NENDAI_HEISEI;
	}
	else if ($g == 2 ) //昭和
	{
		$byear = (int)$y + NENDAI_SYOWA;
	}
	else if ( $g == 1 ) //大正
	{
		$byear = (int)$y + NENDAI_TAISHYOU;
	}
	else if ( $g == 0 ) //明治
	{
		$byear = (int)$y + NENDAI_MEIJI;
	}

	if ( $m == 0 )
	{
		return sprintf("%04d",$byear) ;
	}
	if ( $d == 0 )
	{
		return sprintf("%04d%02d",$byear,$m) ;
	}

	return sprintf("%04d%02d%02d",$byear,$m,$d) ;
}

function is_nendai($g, $year)
{
	if ( $g == 0 )
	{
		if( $year > ( 1 + TAISHYOU - MEIJI ))
		{
			return false;
		}
		return true;
	}

	if ( $g == 1 )
	{
		if( $year > ( 1 + SYOUWA - TAISHYOU ))
		{
			return false;
		}
		return true;
	}

	if ( $g == 2 )
	{
		if( $year > ( 1 + HEISEI - SYOUWA ))
		{
			return false;
		}
		return true;
	}

	if ( $g == 3 )
	{
		if( $year > ( 1 + GENGO - HEISEI ))
		{
			return false;
		}
		return true;
	}

	if ( $g == 4 )
	{
		$cur_year = substr( date( "Ymd"), 0, 4 );
		if( $year > ( 1 + $cur_year - GENGO ))
		{
			return false;
		}
		return true;
	}

	return false;
}

function warekiStrYYYYMMDD($yyyymmdd)
{
	$yyyy	= (int)substr($yyyymmdd,0,4) ;
	$mm	= (int)substr($yyyymmdd,4,2) ;
	$dd	= (int)substr($yyyymmdd,6,2);

	if ( $yyyymmdd <= "19120729" )
	{
		$yyyy	= $yyyy - 1867 ;
		return "明治" . $yyyy . "年" . $mm . "月" . $dd . "日" ;
	}
	elseif ($yyyymmdd >= "19120730" && $yyyymmdd <= "19261224")
	{
		$yyyy	= $yyyy - 1911 ;
		return "大正" . $yyyy . "年" . $mm . "月" . $dd . "日" ;
	}
	elseif ($yyyymmdd >= "19261225" && $yyyymmdd <= "19890107")
	{
		$yyyy	= $yyyy - 1925 ;
		return "昭和" . $yyyy . "年" . $mm . "月" . $dd . "日" ;
	}
	else if ( $yyyymmdd >= "19890108" && $yyyymmdd <= "20190430")
	{
		$yyyy	= $yyyy - 1988 ;
		return "平成" . $yyyy . "年" . $mm . "月" . $dd . "日" ;
	}
	else if ( $yyyymmdd >= "20190501")
	{
		$yyyy	= $yyyy - 2018 ;
		return "令和" . $yyyy . "年" . $mm . "月" . $dd . "日" ;
	}

	return "" ;
}

function warekiStr($str,$mode=0)
{
	//=========================================
	// mode=0 in[YYYY-MM-DD]
	// mode=1 in[YYYYMMDD]  
	//=========================================
	if ( $mode == 0 )
	{
		return warekiStrYYYYMMDD(convertFormat($str,0));
	}

	return warekiStrYYYYMMDD($str);
}

//======================================================
// 処理名：     Check input date is valid or invalid
// 処理ID：     is_valid_date
// 概要  ：     Check input date is valid or invalid
//
// 引数
//	$date_value	   data value
// 戻り値
//	True			:valid
//	False       	:invalid date
//======================================================
function is_valid_date($date_value)
{
	if ( strlen($date_value) != 8 || !check_numeric($date_value) )
	{
		return false;
	}

	$year	= substr($date_value, 0, 4);
	$month	= substr($date_value, 4, 2);
	$day	= substr($date_value, 6, 2);

	if ( $month == 1 || $month == 3 || $month == 5 || $month == 7 ||
	$month == 8 || $month == 10 || $month == 12 )
	{
		if ( $day < 1 || $day > 31 )
		{
			return false;
		}
	}
	else if ( $month == 4 || $month == 6 || $month == 9 || $month == 11 )
	{
		if ( $day < 1 || $day > 30 )
		{
			return false;
		}
	}
	else if ( $month == 2 )
	{
		$wk_y = 28;
		if(( $year % 4 == 0 && $year % 100 != 0 ) || ( $year % 400 == 0 ))
		{
			$wk_y	= 29 ;
		}
		if ( $day < 1 || $day > $wk_y )
		{
			return false;
		}
	}
	else
	{
		return false;
	}

	return true;
}

function getHourF($str)
{
	// HHMM -> HH:MM
	//=========================================
	if( strlen($str) == 4 )
	{
		return substr($str,0,2) ;
	}
	return "" ;
}

function getHourB($str)
{
	// HHMM -> HH:MM
	//=========================================
	if( strlen($str) == 4 )
	{
		return substr($str,2,2) ;
	}
	return "" ;
}

function convHour($str)
{
	return getHourF($str) . ":" . getHourB($str);
}

function makeHour($f,$b)
{
	if ( ( $f === "" || $f === null ) && ( $b === "" || $b === null ) )
	{
		return "" ;
	}
	return sprintf("%02s",$f) . sprintf("%02s",$b) ;
}

function splitWarekiStr($ymd,&$g,&$y,&$m,&$d)
{
	if ( strlen($ymd) != 8 )
	{
		return false;
	}

	$yyyy	= substr($ymd,0,4);
	$m	= substr($ymd,4,2);
	$d	= substr($ymd,6,2);

	if ( $ymd <= "19120729" )
	{
		$g	= 0 ;
		$y	= $yyyy - 1867 ;
	}
	elseif ($ymd >= "19120730" && $ymd <= "19261224")
	{
		$g	= 1 ;
		$y	= $yyyy - 1911 ;
	}
	elseif ($ymd >= "19261225" && $ymd <= "19890107")
	{
		$g	= 2 ;
		$y	= $yyyy - 1925 ;
	}
	elseif ($ymd >= "19890108" && $ymd <= "20190430")
	{
		$g	= 3 ;
		$y	= $yyyy - 1988 ;
	}
	elseif ( $ymd >= "20190501")
	{
		$g	= 4 ;
		$y	= $yyyy - 2018 ;
	}

	return true ;
}

?>
