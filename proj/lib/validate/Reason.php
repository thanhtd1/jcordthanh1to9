<?php
//======================================================
//
// 機能名：	バリデーションエラーなどの内容を保持する。
//
// 機能ID：	Reason.php
// 継承  ：	none
// 概要  ：	下記三つの要素を保持します。
// 	{@code what} ... エラーの発生したデータ項目を指し示す識別子
// 	{@code how} ... エラー発生時のそのデータ項目の値
// 	{@code why} ... 如何なる違反だったのか
// 	{@code params} ... 違反と判断したパラメータ（最大値・最小値など）
//TODO 	{@code position} ... 複数データの場合、何番目か
//
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

class Reason
{
	const	NONE	= 0;
	const	INFO	= 1;
	const	WARNIG	= 2;
	const	ERROR	= 4;

	public	$what	= "";
	public	$how	= "";
	public	$why	= "";
	public	$level	= self::NONE;
	public	$params	= null;

	function __construct($w,$h,$y,$l,...$p)
	{
		$this->what	= $w;
		$this->how	= $h;
		$this->why	= $y;
		$this->level	= $l;
		$this->params	= $p;
	}

	public static function isExists($reasons, $level)
	{
		foreach ($reasons as $err)
		{
			if ( $err->level == $level )
			{
				return true;
			}
		}
		return false;
	}
	public static function isError($reasons)
	{
		return self::isExists($reasons,self::ERROR);
	}

} // CLASS-EOF
?>
