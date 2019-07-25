<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validBank_caseid.php
// 継承  ：	none
// 概要  ：	Valid Bank_caseid class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank_caseid.php");
require_once(DBD_DIR . "dbdBank_caseid.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_L3_DIR . "BVBank_caseid.php");

class validBank_caseid extends BVBank_caseid
{
	function __construct()
	{
		parent::__construct();
	}

	// Bank_caseid 新規登録データチェック
	function validAdd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank_caseid();

		// バンクID
		$l_out = array();
        $l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid, $l_out, $a_err, array('required' => 1));
    
        // バンク毎年毎症例ID
        $l_out = array();
		$l_ret |= $this->check_SEQ_NO($l_dbd_p->l_dbo->m_seq_no, $l_out, $a_err, array('required' => 1));
        
        debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// Bank_caseid 新規登録データチェック
	function validMod($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank_caseid();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));
		// バンクID
		$l_out = array();
        $l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid, $l_out, $a_err, array('required' => 1));
        
        // バンク毎年毎症例ID
        $l_out = array();
        $l_ret |= $this->check_SEQ_NO($l_dbd_p->l_dbo->m_seq_no, $l_out, $a_err, array('required' => 1));
	
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// Bank_caseid 新規登録データチェック
	function validDel($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank_caseid();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));

		debug_log("<< ($l_ret)");
		return $l_ret;
    }

    // valid Get
    function validGet($a_recid, &$a_err) {
		debug_log(">>");
		
		$l_out = array();
		$res = $this->check_RECID($a_recid, $l_out, $a_err,['required'=> 1]);
		debug_log("<< ($res)");
		return $res;
    }
	
	// validGet for bank id
    function validGet_Bankid($a_recid, &$a_err){
		debug_log(">>");
		
		$l_out = array();
		$res = $this->check_BANKID($a_recid, $l_out, $a_err,['required'=>1]);
		debug_log("<< ($res)");
		return $res;
	}

} // CLASS-EOF
?>
