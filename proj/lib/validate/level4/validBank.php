<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validUser.php
// 継承  ：	none
// 概要  ：	Valid User class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank.php");
require_once(DBD_DIR . "dbdBank.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_L3_DIR . "BVBank.php");

class validBank extends BVBank
{
	function __construct()
	{
		parent::__construct();
	}

	// Bank 新規登録データチェック
	function validAdd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank();

		// バンクID
		$l_out = array();
		$l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid, $l_out, $a_err, array('required' => 1));
		// 管理バンクID
		$l_out = array();
		$l_ret |= $this->check_CUR_BANKID($l_dbd_p->l_dbo->m_cur_bankid, $l_out, $a_err, array('required' => 1));
		//有効フラグ
		$l_out = array();
		$l_ret |= $this->check_AVAILABLE($l_dbd_p->l_dbo->m_available, $l_out, $a_err, array('required' => 0));
		// バンク名称
		$l_out = array();
		$l_ret |= $this->check_BANK_NAME($l_dbd_p->l_dbo->m_bank_name, $l_out, $a_err, array('required' => 1));
		// 省略名称
		$l_out = array();
		$l_ret |= $this->check_SHORT_NAME($l_dbd_p->l_dbo->m_short_name, $l_out, $a_err, array('required' => 0));
		// 英語名称
		$l_out = array();
		$l_ret |= $this->check_EMPNAME($l_dbd_p->l_dbo->m_ename, $l_out, $a_err, array('required' => 0));
		// 英語省略名称
		$l_out = array();
		$l_ret |= $this->check_SHORT_EMPNAME($l_dbd_p->l_dbo->m_short_ename, $l_out, $a_err, array('required' => 1));
		// 担当者
		$l_out = array();
		$l_ret |= $this->check_PERSON($l_dbd_p->l_dbo->m_person, $l_out, $a_err, array('required' => 0));
		// 電話番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM($l_dbd_p->l_dbo->m_tel_num, $l_out, $a_err, array('required' => 0));

		// FAX番号
		$l_out = array();
		$l_ret |= $this->check_FAX_NUM($l_dbd_p->l_dbo->m_fax_num, $l_out, $a_err, array('required' => 0));

		// バンク種別
		$l_out = array();
		$l_ret |= $this->check_KIND($l_dbd_p->l_dbo->m_kind, $l_out, $a_err, array('required' => 0));

		// バンク並び順
		$l_out = array();
		$l_ret |= $this->check_ROW_NTH($l_dbd_p->l_dbo->m_row_nth, $l_out, $a_err, array('required' => 0));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// Bank 新規登録データチェック
	function validMod($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));
		// バンクID
		$l_out = array();
		$l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid, $l_out, $a_err, array('required' => 1));
		// 管理バンクID
		$l_out = array();
		$l_ret |= $this->check_CUR_BANKID($l_dbd_p->l_dbo->m_cur_bankid, $l_out, $a_err, array('required' => 1));
		//有効フラグ
		$l_out = array();
		$l_ret |= $this->check_AVAILABLE($l_dbd_p->l_dbo->m_available, $l_out, $a_err, array('required' => 0));
		// バンク名称
		$l_out = array();
		$l_ret |= $this->check_BANK_NAME($l_dbd_p->l_dbo->m_bank_name, $l_out, $a_err, array('required' => 1));
		// 省略名称
		$l_out = array();
		$l_ret |= $this->check_SHORT_NAME($l_dbd_p->l_dbo->m_short_name, $l_out, $a_err, array('required' => 0));
		// 英語名称
		$l_out = array();
		$l_ret |= $this->check_EMPNAME($l_dbd_p->l_dbo->m_ename, $l_out, $a_err, array('required' => 0));
		// 英語省略名称
		$l_out = array();
		$l_ret |= $this->check_SHORT_EMPNAME($l_dbd_p->l_dbo->m_short_ename, $l_out, $a_err, array('required' => 1));
		// 担当者
		$l_out = array();
		$l_ret |= $this->check_PERSON($l_dbd_p->l_dbo->m_person, $l_out, $a_err, array('required' => 0));
		// 電話番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM($l_dbd_p->l_dbo->m_tel_num, $l_out, $a_err, array('required' => 0));

		// FAX番号
		$l_out = array();
		$l_ret |= $this->check_FAX_NUM($l_dbd_p->l_dbo->m_fax_num, $l_out, $a_err, array('required' => 0));

		// バンク種別
		$l_out = array();
		$l_ret |= $this->check_KIND($l_dbd_p->l_dbo->m_kind, $l_out, $a_err, array('required' => 0));

		// バンク並び順
		$l_out = array();
		$l_ret |= $this->check_ROW_NTH($l_dbd_p->l_dbo->m_row_nth, $l_out, $a_err, array('required' => 0));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// Bank 新規登録データチェック
	function validDel($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDBank();

		// バンクID
		$l_out = array();
		$l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid, $l_out, $a_err, array('required' => 1));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// Valid get
	function validGet($a_recid, &$a_err){
		debug_log(">>");
		$l_ret = self::VALID_OK;
		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($a_recid, $l_out, $a_err, array('required' => 1));
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

} // CLASS-EOF
?>
