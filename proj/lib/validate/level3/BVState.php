<?php
//======================================================
//
// 機能名：	状態ェック Class.
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

	// check recid
	function check_RECID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check recipid
	function check_RECIPID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check cordid
	function check_CORDID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check reg_stat
	function check_REG_STAT($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check user_id
	function check_USER_ID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check reserve_date
	function check_RESERVE_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check cancel_date
	function check_CANCEL_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check supply_date
	function check_SUPPLY_DATE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check supply_hosp
	function check_SUPPLY_HOSP($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check hosp_code
	function check_HOSP_CODE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check rank
	function check_RANK($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check userid
	function check_USERID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check cocktail
	function check_COCKTAIL($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check search_number
	function check_SEARCH_NUMBER($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check fit_number
	function check_FIT_NUMBER($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	// check note
	function check_NOTE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

} // CLASS-EOF
?>
