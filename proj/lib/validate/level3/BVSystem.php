<?php
//======================================================
//
// 機能名：	状態ェック Class.
//
// 機能ID：	BVSystem.php
// 継承  ：	none
// 概要  ：	Valid System class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdSystem.php");
require_once(DBD_DIR . "dbdSystem.php");

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

class BVSystem extends Valid
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

	// check recid
	function check_AVAILABLE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}
	
	// check Item_name
	function check_ITEM_NAME($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check recid
	function check_ITEM_VALUE($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)") ;

		$ret = self::VALID_OK;

		//実行
		//TODO::

		// エラーなし
		debug_log("<<") ;
		return $ret ;
	}

	// check recid
	function check_ITEM_NOTE($a_val,&$a_out,&$a_err,$a_opt = array())
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
