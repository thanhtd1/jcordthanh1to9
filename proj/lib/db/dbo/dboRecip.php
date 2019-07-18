<?php
require_once(DBO_DIR . "dboCore.php");

class dboRecip extends dboCore {

	// メンバ定数
	const M_BIRTHDAY	= "birthday";	// 生年月日
	const M_HLA_A1	= "hla_a1";	// HLA-A(1)
	const M_HLA_A2	= "hla_a2";	// HLA-A(2)
	const M_HLA_B1	= "hla_b1";	// HLA-B(1)
	const M_HLA_B2	= "hla_b2";	// HLA-B(2)
	const M_HLA_DR1	= "hla_dr1";	// HLA-DR(1)
	const M_HLA_DR2	= "hla_dr2";	// HLA-DR(2)
	const M_BLOOD_ABO	= "blood_abo";	// ABO血液型
	const M_BLOOD_RH	= "blood_rh";	// Rh血液型
	const M_SEX	= "sex";	// 性別
	const M_CANCEL_DATE	= "cancel_date";	// 最新取消日
	const M_RESERVE_DATE	= "reserve_date";	// 最古申込日
	const M_NOTE	= "note";	// 備考
	const M_ACCEPT	= "accept";	// 受理フラグ
	const M_WEIGHT	= "weight";	// 体重
	const M_MATCH_NUM	= "match_num";	// 適合抗原数
	const M_RESULT_SORT	= "result_sort";	// 検索結果整列順
	const M_RESULT_NUM	= "result_num";	// 検索結果表示数
	const M_USERID	= "userid";	// 施設ユーザID
	const M_HLA_CW1	= "hla_cw1";	// HLA-Cw(1)
	const M_HLA_CW2	= "hla_cw2";	// HLA-Cw(2)
	const M_HLA_DQ1	= "hla_dq1";	// HLA-DQ(1)
	const M_HLA_DQ2	= "hla_dq2";	// HLA-DQ(2)
	const M_A_1	= "a_1";	// A(1)
	const M_A_2	= "a_2";	// A(2)
	const M_B_1	= "b_1";	// B(1)
	const M_B_2	= "b_2";	// B(2)
	const M_C_1	= "c_1";	// C(1)
	const M_C_2	= "c_2";	// C(2)
	const M_DRB1_1	= "drb1_1";	// DRB1(1)
	const M_DRB1_2	= "drb1_2";	// DRB1(2)
	const M_DQB1_1	= "dqb1_1";	// DQB1(1)
	const M_DQB1_2	= "dqb1_2";	// DQB1(2)

	// メンバ変数
	public $m_birthday;	// 生年月日
	public $m_hla_a1;	// HLA-A(1)
	public $m_hla_a2;	// HLA-A(2)
	public $m_hla_b1;	// HLA-B(1)
	public $m_hla_b2;	// HLA-B(2)
	public $m_hla_dr1;	// HLA-DR(1)
	public $m_hla_dr2;	// HLA-DR(2)
	public $m_blood_abo;	// ABO血液型
	public $m_blood_rh;	// Rh血液型
	public $m_sex;	// 性別
	public $m_cancel_date;	// 最新取消日
	public $m_reserve_date;	// 最古申込日
	public $m_note;	// 備考
	public $m_accept;	// 受理フラグ
	public $m_weight;	// 体重
	public $m_match_num;	// 適合抗原数
	public $m_result_sort;	// 検索結果整列順
	public $m_result_num;	// 検索結果表示数
	public $m_userid;	// 施設ユーザID
	public $m_hla_cw1;	// HLA-Cw(1)
	public $m_hla_cw2;	// HLA-Cw(2)
	public $m_hla_dq1;	// HLA-DQ(1)
	public $m_hla_dq2;	// HLA-DQ(2)
	public $m_a_1;	// A(1)
	public $m_a_2;	// A(2)
	public $m_b_1;	// B(1)
	public $m_b_2;	// B(2)
	public $m_c_1;	// C(1)
	public $m_c_2;	// C(2)
	public $m_drb1_1;	// DRB1(1)
	public $m_drb1_2;	// DRB1(2)
	public $m_dqb1_1;	// DQB1(1)
	public $m_dqb1_2;	// DQB1(2)

	// 
	private $insert_item;
	private $insert_key;
	private $update_item;
	private $update_key;
	private $delete_item;
	private $delete_key;
	private $harddelete_item;
	private $harddelete_key;
	private $get_item;
	private $get_key;
	private $select_item;
	private $select_key;

	public function __construct() {
		parent::__construct();

		$this->m_del_flag = 0;

		// ↓ここにSQLで必要な定義を作成していくこと。
		$this->insert_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_RECID,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_REG_DATE,
		      $this::M_REG_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_BIRTHDAY,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_CANCEL_DATE,
		      $this::M_RESERVE_DATE,
		      $this::M_NOTE,
		      $this::M_ACCEPT,
		      $this::M_WEIGHT,
		      $this::M_MATCH_NUM,
		      $this::M_RESULT_SORT,
		      $this::M_RESULT_NUM,
		      $this::M_USERID,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DQ1,
		      $this::M_HLA_DQ2,
		      $this::M_A_1,
		      $this::M_A_2,
		      $this::M_B_1,
		      $this::M_B_2,
		      $this::M_C_1,
		      $this::M_C_2,
		      $this::M_DRB1_1,
		      $this::M_DRB1_2,
		      $this::M_DQB1_1,
		      $this::M_DQB1_2);
		$this->insert_key = array();

		$this->update_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_BIRTHDAY,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_CANCEL_DATE,
		      $this::M_RESERVE_DATE,
		      $this::M_NOTE,
		      $this::M_ACCEPT,
		      $this::M_WEIGHT,
		      $this::M_MATCH_NUM,
		      $this::M_RESULT_SORT,
		      $this::M_RESULT_NUM,
		      $this::M_USERID,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DQ1,
		      $this::M_HLA_DQ2,
		      $this::M_A_1,
		      $this::M_A_2,
		      $this::M_B_1,
		      $this::M_B_2,
		      $this::M_C_1,
		      $this::M_C_2,
		      $this::M_DRB1_1,
		      $this::M_DRB1_2,
		      $this::M_DQB1_1,
		      $this::M_DQB1_2);
		$this->update_key = array($this::M_RECID);

		$this->delete_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG);
		$this->delete_key = array($this::M_RECID);

		$this->harddelete_item = array();
		$this->harddelete_key = array($this::M_RECID);

		$this->get_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_RECID,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_REG_DATE,
		      $this::M_REG_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_BIRTHDAY,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_CANCEL_DATE,
		      $this::M_RESERVE_DATE,
		      $this::M_NOTE,
		      $this::M_ACCEPT,
		      $this::M_WEIGHT,
		      $this::M_MATCH_NUM,
		      $this::M_RESULT_SORT,
		      $this::M_RESULT_NUM,
		      $this::M_USERID,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DQ1,
		      $this::M_HLA_DQ2,
		      $this::M_A_1,
		      $this::M_A_2,
		      $this::M_B_1,
		      $this::M_B_2,
		      $this::M_C_1,
		      $this::M_C_2,
		      $this::M_DRB1_1,
		      $this::M_DRB1_2,
		      $this::M_DQB1_1,
		      $this::M_DQB1_2);
		$this->get_key = array($this::M_RECID);

		$this->select_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_RECID,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_REG_DATE,
		      $this::M_REG_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_BIRTHDAY,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_CANCEL_DATE,
		      $this::M_RESERVE_DATE,
		      $this::M_NOTE,
		      $this::M_ACCEPT,
		      $this::M_WEIGHT,
		      $this::M_MATCH_NUM,
		      $this::M_RESULT_SORT,
		      $this::M_RESULT_NUM,
		      $this::M_USERID,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DQ1,
		      $this::M_HLA_DQ2,
		      $this::M_A_1,
		      $this::M_A_2,
		      $this::M_B_1,
		      $this::M_B_2,
		      $this::M_C_1,
		      $this::M_C_2,
		      $this::M_DRB1_1,
		      $this::M_DRB1_2,
		      $this::M_DQB1_1,
		      $this::M_DQB1_2);
		$this->select_key = array();
		// ↑ここまで
	}

	public function getInsertItem()
	{
		return $this->insert_item;
	}

	public function getInsertKey()
	{
		return $this->insert_key;
	}

	public function getUpdateItem()
	{
		return $this->update_item;
	}

	public function getUpdateKey()
	{
		return $this->update_key;
	}

	public function getDeleteItem()
	{
		return $this->delete_item;
	}

	public function getDeleteKey()
	{
		return $this->delete_key;
	}

	public function getHardDeleteItem()
	{
		return $this->harddelete_item;
	}

	public function getHardDeleteKey()
	{
		return $this->harddelete_key;
	}

	public function getGetItem()
	{
		return $this->get_item;
	}

	public function getGetKey()
	{
		return $this->get_key;
	}

	public function getSelectItem()
	{
		return $this->select_item;
	}

	public function getSelectKey()
	{
		return $this->select_key;
	}
}
?>
