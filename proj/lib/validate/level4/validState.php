<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validState.php
// 継承  ：	none
// 概要  ：	Valid State class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdState.php");
require_once(DBD_DIR . "dbdState.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_L3_DIR . "BVState.php");

class validState extends BVState
{
	function __construct()
	{
		parent::__construct();
	}

	// State 新規登録データチェック
	function validAdd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDState();

		// 患者ID
		$l_out = array();
		$l_ret |= $this->check_RECIPID($l_dbd_p->l_dbo->m_recipid, $l_out, $a_err, array('required' => 0));

		// さい帯血ID
		$l_out = array();
		$l_ret |= $this->check_CORDID($l_dbd_p->l_dbo->m_cordid, $l_out, $a_err, array('required' => 1));

		// 状態
		$l_out = array();
		$l_ret |= $this->check_REG_STAT($l_dbd_p->l_dbo->m_reg_stat, $l_out, $a_err, array('required' => 1));

		// 更新ユーザID
		$l_out = array();
		$l_ret |= $this->check_USER_ID($l_dbd_p->l_dbo->m_user_id, $l_out, $a_err, array('required' => 0));

		// 申込日
		$l_out = array();
		$l_ret |= $this->check_RESERVE_DATE($l_dbd_p->l_dbo->m_reserve_date, $l_out, $a_err, array('required' => 0));

		// 申込日
		$l_out = array();
		$l_ret |= $this->check_CANCEL_DATE($l_dbd_p->l_dbo->m_cancel_date, $l_out, $a_err, array('required' => 0));

		// 供給年月日
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_DATE($l_dbd_p->l_dbo->m_supply_date, $l_out, $a_err, array('required' => 0));
		
		// 供給病院
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_HOSP($l_dbd_p->l_dbo->m_supply_hosp, $l_out, $a_err, array('required' => 0));

		// 供給病院
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_HOSP($l_dbd_p->l_dbo->m_supply_hosp, $l_out, $a_err, array('required' => 0));

		// 施設コード
		$l_out = array();
		$l_ret |= $this->check_HOSP_CODE($l_dbd_p->l_dbo->m_hosp_code, $l_out, $a_err, array('required' => 0));
		
		// 適合ランク
		$l_out = array();
		$l_ret |= $this->check_RANK($l_dbd_p->l_dbo->m_rank, $l_out, $a_err, array('required' => 0));
		
		// 施設ユーザID
		$l_out = array();
		$l_ret |= $this->check_USERID($l_dbd_p->l_dbo->m_userid, $l_out, $a_err, array('required' => 0));
		
		// カクテル移植フラグ
		$l_out = array();
		$l_ret |= $this->check_COCKTAIL($l_dbd_p->l_dbo->m_cocktail, $l_out, $a_err, array('required' => 0));

		// 検索数
		$l_out = array();
		$l_ret |= $this->check_SEARCH_NUMBER($l_dbd_p->l_dbo->m_search_number, $l_out, $a_err, array('required' => 0));

		// 適合数
		$l_out = array();
		$l_ret |= $this->check_FIT_NUMBER($l_dbd_p->l_dbo->m_fit_number, $l_out, $a_err, array('required' => 0));
		
		// 備考
		$l_out = array();
		$l_ret |= $this->check_NOTE($l_dbd_p->l_dbo->m_note, $l_out, $a_err, array('required' => 0));
		
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// State 新規登録データチェック
	function validMod($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDState();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));

		// 患者ID
		$l_out = array();
		$l_ret |= $this->check_RECIPID($l_dbd_p->l_dbo->m_recipid, $l_out, $a_err, array('required' => 0));

		// さい帯血ID
		$l_out = array();
		$l_ret |= $this->check_CORDID($l_dbd_p->l_dbo->m_cordid, $l_out, $a_err, array('required' => 1));

		// 状態
		$l_out = array();
		$l_ret |= $this->check_REG_STAT($l_dbd_p->l_dbo->m_reg_stat, $l_out, $a_err, array('required' => 1));

		// 更新ユーザID
		$l_out = array();
		$l_ret |= $this->check_USER_ID($l_dbd_p->l_dbo->m_user_id, $l_out, $a_err, array('required' => 0));

		// 申込日
		$l_out = array();
		$l_ret |= $this->check_RESERVE_DATE($l_dbd_p->l_dbo->m_reserve_date, $l_out, $a_err, array('required' => 0));

		// 申込日
		$l_out = array();
		$l_ret |= $this->check_CANCEL_DATE($l_dbd_p->l_dbo->m_cancel_date, $l_out, $a_err, array('required' => 0));

		// 供給年月日
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_DATE($l_dbd_p->l_dbo->m_supply_date, $l_out, $a_err, array('required' => 0));
		
		// 供給病院
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_HOSP($l_dbd_p->l_dbo->m_supply_hosp, $l_out, $a_err, array('required' => 0));

		// 供給病院
		$l_out = array();
		$l_ret |= $this->check_SUPPLY_HOSP($l_dbd_p->l_dbo->m_supply_hosp, $l_out, $a_err, array('required' => 0));

		// 施設コード
		$l_out = array();
		$l_ret |= $this->check_HOSP_CODE($l_dbd_p->l_dbo->m_hosp_code, $l_out, $a_err, array('required' => 0));
		
		// 適合ランク
		$l_out = array();
		$l_ret |= $this->check_RANK($l_dbd_p->l_dbo->m_rank, $l_out, $a_err, array('required' => 0));
		
		// 施設ユーザID
		$l_out = array();
		$l_ret |= $this->check_USERID($l_dbd_p->l_dbo->m_userid, $l_out, $a_err, array('required' => 0));
		
		// カクテル移植フラグ
		$l_out = array();
		$l_ret |= $this->check_COCKTAIL($l_dbd_p->l_dbo->m_cocktail, $l_out, $a_err, array('required' => 0));

		// 検索数
		$l_out = array();
		$l_ret |= $this->check_SEARCH_NUMBER($l_dbd_p->l_dbo->m_search_number, $l_out, $a_err, array('required' => 0));

		// 適合数
		$l_out = array();
		$l_ret |= $this->check_FIT_NUMBER($l_dbd_p->l_dbo->m_fit_number, $l_out, $a_err, array('required' => 0));
		
		// 備考
		$l_out = array();
		$l_ret |= $this->check_NOTE($l_dbd_p->l_dbo->m_note, $l_out, $a_err, array('required' => 0));
	
		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// State 新規登録データチェック
	function validDel($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDState();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid, $l_out, $a_err, array('required' => 1));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	function validGet($a_recid, &$a_err){
		debug_log(">>");
		
		$l_out = array();
		$l_ret = $this->check_RECID($a_recid, $l_out, $a_err,array('required' => 1));
		debug_log("<< ($l_ret)");
		return $l_ret;
	}
} // CLASS-EOF
?>
