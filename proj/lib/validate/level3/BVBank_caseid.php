<?php
//======================================================
//
// 機能名：	ユーザチェック Class.
//
// 機能ID：	BVBank_caseid.php
// 継承  ：	none
// 概要  ：	Valid Bank_caseid class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "convert.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank_caseid.php");
require_once(DBD_DIR . "dbdBank_caseid.php");

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

class BVBank_caseid extends Valid
{
	// 有効フラグ
	private $m_available = [0,1];

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

    // check bankid
	function check_BANKID($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

        //実行
        //TODO::

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
    }
    
    // check seq_no
    function check_SEQ_NO($a_val,&$a_out,&$a_err,$a_opt = array())
	{
		debug_log(">> ($a_val)");

		$ret = self::VALID_OK;

        //実行
        //TODO::

		// エラーなし
		debug_log("<< ($ret)");
		return $ret;
    }
	
} // CLASS-EOF
?>
