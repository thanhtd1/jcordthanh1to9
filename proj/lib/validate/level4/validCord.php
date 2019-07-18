<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validCord.php
// 継承  ：	none
// 概要  ：	Valid Cord class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdCord.php");
require_once(DBD_DIR . "dbdCord.php");

require_once(VALID_DIR . "Valid.php");

class validCord extends Valid
{
	// 登録状態の既定値
	private $m_def_reg_stat = [0,1,2,3,4,8,9,10];
	// 性別の既定値
	private $m_def_sex = [1,2];
	// ABO血液型の既定値
	private $m_def_blood_abo = ['A','B','O','AB'];
	// Rh血液型の既定値
	private $m_def_blood_rh = ['+','-'];
	// 分離方法の既定値
	private $m_def_separate = [1,2,3];
	// 凍結方法の既定値
	private $m_def_freezing = [1,2];
	// CMV-IgM検査結果の既定値
	private $m_def_cmvigm = [1,2,3,4,5];
	// CMV-DNA検査結果の既定値
	private $m_def_cmvdna = [1,2,3,4,5];
	// 移植実施報告フラグの既定値
	private $m_def_trans_flag = [1,2];

	function __construct()
	{
		parent::__construct();
	}

	//======================================================
	// 処理名：	RECID
	// 処理ID：	check_RECID
	// 概要	：	RECIDのチェックをする
	//		型	:数値
	//		全半角	:
	//		最小桁	:
	//		最大桁	:
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	// 引数
	//	$val	:値
	//	$emp	:必須の有無(true:必須,false:任意)
	//
	// 戻り値
	//	0	:正常
	//	0以外	:エラーコード
	//======================================================
	function check_RECID($val,$emp,&$err)
	{
		debug_log(">> ($val,$emp)") ;

		// 必須判定(emp=true時)
		if ( self::check_null($val) === true )
		{
			if ( $emp === true )
			{
				debug_log("<< null") ;
				$err[]	= self::err("recid","null","RECIDは必須です。",Valid::VALID_LEVE_ERR);
				return self::VALID_NULL_ERR ;
			}
			else
			{
				// エラーなし
				debug_log("<<") ;
				return self::VALID_OK ;
			}
		}

		if ( self::check_numeric($val, false) === false) {
			debug_log("<< length") ;
			$err[]	= self::err("recid",$val,"RECIDの形式が不正です。",Valid::VALID_LEVE_ERR);
			return self::VALID_NUMERIC_ERR  ;
		}

		// エラーなし
		debug_log("<<") ;
		return self::VALID_OK ;
	}

	//======================================================
	//	処理名：	バンクID
	//	処理ID：	check_BANKID
	//	概要  ：	ユーザ名のチェックをする
	//		型	:数値
	//		全半角	:半角
	//		最小桁	:
	//		最大桁	:
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	//	引数
	//	$val	:値
	//	$emp	:必須の有無(true:必須,false:任意)
	//
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	function	check_BANKID($val,$emp,&$err)
	{
		debug_log(">> ($val,$emp)");

		//	必須判定(emp=true時)
		if ( self::check_null($val) === true )
		{
			if ( $emp === true )
			{
				debug_log("<< null");
				$err[] = self::err("bankid","null","バンクIDは必須です。",Valid::VALID_LEVE_ERR);
				return self::VALID_NULL_ERR;
			}
			else
			{
				//	エラーなし
				debug_log("<<");
				return self::VALID_OK;
			}
		}

		// 数値チェック
		if ( self::check_numeric($val, false) === false) {
			$err[] = self::err("bankid",$val,"バンクIDの形式が不正です。",Valid::VALID_LEVE_ERR);
			return self::VALID_NUMERIC_ERR;
		}

		//	エラーなし
		debug_log("<<");
		return self::VALID_OK;
	}

	//======================================================
	//	処理名：	調整バンクID
	//	処理ID：	check_RECEIPT_BANKID
	//	概要  ：	調整バンクIDのチェックをする
	//		型	:数値
	//		全半角	:半角
	//		最小桁	:
	//		最大桁	:
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	//	引数
	//	$val		:値
	//	$emp		:必須の有無(true:必須,false:任意)
	//
	//	戻り値
	//	0		:正常
	//	0以外		:エラーコード
	//======================================================
	function	check_RECEIPT_BANKID($val,$emp,&$err)
	{
		debug_log(">> ($val,$emp)");

		//      必須判定(emp=true時)
		if ( self::check_null($val) === true )
		{
			if ( $emp === true )
			{
				debug_log("<< null");
				$err[] = self::err("receipt_bankid","null","調整バンクIDは必須です。",Valid::VALID_LEVE_ERR);
				return self::VALID_NULL_ERR;
			}
			else
			{
				//      エラーなし
				debug_log("<<");
				return self::VALID_OK;
			}
		}

		// 数値チェック
		if ( self::check_numeric($val, false) === false) {
			$err[] = self::err("receipt_bankid",$val,"調整バンクIDの形式が不正です。",Valid::VALID_LEVE_ERR);
			return self::VALID_NUMERIC_ERR;
		}

		//      エラーなし
		debug_log("<<");
		return self::VALID_OK;
	}

	//======================================================
	//	処理名：	登録状態
	//	処理ID：	check_REG_STAT
	//	概要  ：	登録状態のチェックをする
	//		型	:半角数字
	//		全半角	:半角
	//		最小桁	:1
	//		最大桁	:2
	//		最小値	:
	//		最大値	:
	//		範囲	:
	//
	//	引数
	//	$val	:値
	//	$emp	:必須の有無(true:必須,false:任意)
	//
	//	戻り値
	//	0		:正常
	//	0以外	:エラーコード
	//======================================================
	const	REG_STAT_L_LEN	 = 1 ;
	const	REG_STAT_U_LEN	 = 2 ;
	function	check_REG_STAT($val,$emp,&$err)
	{
		debug_log(">> ($val,$emp)");

		//	必須判定(emp=true時)
		if ( self::check_null($val) === true )
		{
			if ( $emp === true )
			{
				debug_log("<< null");
				$err[] = self::err("reg_stat","null","登録状態は必須です。",Valid::VALID_LEVE_ERR);
				return self::VALID_NULL_ERR;
			}
			else
			{
				//	エラーなし
				debug_log("<<");
				return self::VALID_OK;
			}
		}

		// 数値チェック
		if ( self::check_numeric($val, false) === false) {
			$err[] = self::err("reg_stat",$val,"登録状態の形式が不正です。",Valid::VALID_LEVE_ERR);
			return self::VALID_NUMERIC_ERR;
		}

		// 長さチェック
		if ( self::check_length($val,false,self::REG_STAT_L_LEN,self::REG_STAT_U_LEN) === false )
		{
			debug_log("<< length") ;
			$err[]	= self::err("name",$val,"登録状態の長さが不正です。",Valid::VALID_LEVE_ERR);
			return self::VALID_LENGTH_ERR  ;
		}

		// 既定値チェック
		if ( self::check_default($val,false,$this->m_def_reg_stat) === false )
		{
			$err[]  = self::err("name",$val,"登録状態に不正な値が入力されています。",Valid::VALID_LEVE_ERR);
			return self::VALID_DEFAULT_ERR  ;
		}

		//	エラーなし
		debug_log("<<");
		return self::VALID_OK;
	}

	//======================================================
	// 処理名：	Cordデータチェック
	// 処理ID：	isValid
	// 概要  ：	CordデータdataCordをチェックする
	//
	// 引数
	//	$apd	:データ(apdCord)
	//	$prm	:0(新規),1(更新)
	//		
	// 戻り値
	//	array	:エラーコードのArray
	//======================================================
	function isValid($apd,$prm,&$err)
	{
		debug_log(">>()") ;
		debug_log(print_r($apd,True));

		$rtn	= 1;
		$dbd_p	= $apd->getDBDCord();
		// == 単体チェック ==============================================
		$emp = false;
		if ($prm === 1) {
			$emp = true;
		}
		// RECIDのチェック
		if ( self::check_RECID($dbd_p->l_dbo->m_recid,$emp,$err) !== self::VALID_OK )
		{
			$rtn = 0;
		}
		// バンクIDのチェック
		if ( self::check_BANKID($dbd_p->l_dbo->m_bankid,true,$err) !== self::VALID_OK )
		{
			$rtn = 0;
		}
		// バンク内管理番号のチェック

		// 調整バンクコードのチェック
		if ( self::check_RECEIPT_BANKID($dbd_p->l_dbo->m_receipt_bankid,true,$err) !== self::VALID_OK )
		{
			$rtn = 0;
		}
		// 調整バンク内管理番号のチェック

		// 登録状態のチェック
		if ( self::check_REG_STAT($dbd_p->l_dbo->m_reg_stat,true,$err) !== self::VALID_OK )
		{
			$rtn = 0;
		}
		// HLA-A(1)のチェック
		// HLA-A(2)のチェック
		// HLA-B(1)のチェック
		// HLA-B(2)のチェック
		// HLA-Cw(1)のチェック
		// HLA-Cw(2)のチェック
		// HLA-DR(1)のチェック
		// HLA-DR(2)のチェック
		// HLA-DQ(1)のチェック
		// HLA-DQ(2)のチェック
		// A(1)のチェック
		// A(2)のチェック
		// B(1)のチェック
		// B(2)のチェック
		// C(1)のチェック
		// C(2)のチェック
		// DRB1(1)のチェック
		// DRB1(2)のチェック
		// DQB1(1)のチェック
		// DQB1(2)のチェック
		// 採取年月日のチェック
		// ABO血液型のチェック
		// Rh血液型のチェック
		// 性別のチェック
		// 分離方法のチェック
		// 凍結方法のチェック
		// 凍害保護液のチェック
		// 保存液量のチェック
		// 保存温度のチェック
		// 有効細胞数のチェック
		// CD34細胞数のチェック
		// CD34測定方法のチェック
		// CFU総数のチェック
		// CFU-GM数のチェック
		// CFU測定方法のチェック
		// CMV-IgM検査結果のチェック
		// CMV-DNA検査結果のチェック
		// 備考のチェック
		// 供給年月日のチェック
		// 供給病院のチェック
		// 移植年月日のチェック
		// 移植実施報告のチェック
		// 施設コードのチェック
		// 赤血球率のチェック
		// 症例番号のチェック
		// TRUMP施設コードのチェック
		// 採取時有効細胞数のチェック
		// 採取時液量のチェック

		// == 関連チェック ==============================================

		debug_log("<< $rtn" );
		return $rtn;
	}

	// Cord 新規データチェック
	function validAdd($apd,&$err) {
		return $this->isValid($apd,0,$err);
	}

	// Cord 更新データチェック
	function validMod($apd,&$err) {
		return $this->isValid($apd,1,$err);
	}
} // CLASS-EOF
?>
