<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validSystem.php
// 継承  ：	none
// 概要  ：	Valid System class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdSystem.php");
require_once(DBD_DIR . "dbdSystem.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_L3_DIR . "BVSystem.php");

class validSystem extends BVSystem
{
	function __construct()
	{
		parent::__construct();
	}

	// System 新規登録データチェック
	function validAdd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDSystem();

		$l_out = array();
        $l_ret |= $this->check_AVAILABLE($l_dbd_p->l_dbo->m_available, $l_out, $a_err, array('required' => 0));

		$l_out = array();
        $l_ret |= $this->check_ITEM_NAME($l_dbd_p->l_dbo->m_item_name, $l_out, $a_err, array('required' => 1));

		$l_out = array();
        $l_ret |= $this->check_ITEM_VALUE($l_dbd_p->l_dbo->m_item_value, $l_out, $a_err, array('required' => 0));

		$l_out = array();
        $l_ret |= $this->check_ITEM_NOTE($l_dbd_p->l_dbo->m_item_note, $l_out, $a_err, array('required' => 0));
    
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// System 新規登録データチェック
	function validMod($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDSystem();

		
        $l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));
        
		$l_out = array();
        $l_ret |= $this->check_AVAILABLE($l_dbd_p->l_dbo->m_available, $l_out, $a_err, array('required' => 0));

		$l_out = array();
        $l_ret |= $this->check_ITEM_NAME($l_dbd_p->l_dbo->m_item_name, $l_out, $a_err, array('required' => 1));

		$l_out = array();
        $l_ret |= $this->check_ITEM_VALUE($l_dbd_p->l_dbo->m_item_value, $l_out, $a_err, array('required' => 0));

		$l_out = array();
        $l_ret |= $this->check_ITEM_NOTE($l_dbd_p->l_dbo->m_item_note, $l_out, $a_err, array('required' => 0));
	
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// System 新規登録データチェック
	function validDel($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDSystem();

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
	
	// validGet for item_name
    function validGet_item_name($a_item_name, &$a_err){
		debug_log(">>");
		
		$l_out = array();
		$res = $this->check_ITEM_NAME($a_item_name, $l_out, $a_err,['required'=> 1]);
		debug_log("<< ($res)");
		return $res;
	}

} // CLASS-EOF
?>
