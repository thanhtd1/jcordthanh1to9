<?php
require_once(DBO_DIR . "dboCore.php");

class dboCord extends dboCore {

	// メンバ定数
	const M_BANKID	= "bankid";	// バンクコード
	const M_BANK_NO	= "bank_no";	// バンク内管理番号
	const M_RECEIPT_BANKID	= "receipt_bankid";	// 調整バンクコード
	const M_RECEIPT_BANK_NO	= "receipt_bank_no";	// 調整バンク内管理番号
	const M_REG_STAT	= "reg_stat";	// 登録状態
	const M_HLA_A1	= "hla_a1";	// HLA-A(1)
	const M_HLA_A2	= "hla_a2";	// HLA-A(2)
	const M_HLA_B1	= "hla_b1";	// HLA-B(1)
	const M_HLA_B2	= "hla_b2";	// HLA-B(2)
	const M_HLA_CW1	= "hla_cw1";	// HLA-Cw(1)
	const M_HLA_CW2	= "hla_cw2";	// HLA-Cw(2)
	const M_HLA_DR1	= "hla_dr1";	// HLA-DR(1)
	const M_HLA_DR2	= "hla_dr2";	// HLA-DR(2)
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
	const M_COLLECT_DATE	= "collect_date";	// 採取年月日
	const M_BLOOD_ABO	= "blood_abo";	// ABO血液型
	const M_BLOOD_RH	= "blood_rh";	// Rh血液型
	const M_SEX	= "sex";	// 性別
	const M_SEPARATE_METHOD	= "separate_method";	// 分離方法
	const M_FREEZING_METHOD	= "freezing_method";	// 凍結方法
	const M_PROTECT_LIQ	= "protect_liq";	// 凍害保護液
	const M_PRESERVE_VOL	= "preserve_vol";	// 保存液量
	const M_PRESERVE_TEMP	= "preserve_temp";	// 保存温度
	const M_CELL_NUM	= "cell_num";	// 有効細胞数
	const M_CD34_NUM	= "cd34_num";	// CD34細胞数
	const M_CD34_METHOD	= "cd34_method";	// CD34測定方法
	const M_CFU_NUM	= "cfu_num";	// CFU総数
	const M_CFUGM_NUM	= "cfugm_num";	// CFU-GM数
	const M_CFUGM_METHOD	= "cfugm_method";	// CFU測定方法
	const M_CMVIGM_METHOD	= "cmvigm_method";	// CMV-IgM検査結果
	const M_CMVDNA_METHOD	= "cmvdna_method";	// CMV-DNA検査結果
	const M_NOTE	= "note";	// 備考
	const M_SUPPLY_DATE	= "supply_date";	// 供給年月日
	const M_SUPPLY_HOSP	= "supply_hosp";	// 供給病院
	const M_TRANS_DATE	= "trans_date";	// 移植年月日
	const M_TRANS_FLAG	= "trans_flag";	// 移植実施報告
	const M_HOSP_CODE	= "hosp_code";	// 施設コード
	const M_RBC_RATE	= "rbc_rate";	// 赤血球率
	const M_CASEID	= "caseid";	// 症例番号
	const M_TRUMP_HOSP_CODE	= "trump_hosp_code";	// TRUMP施設コード
	const M_HARVEST_CELL_NUM	= "harvest_cell_num";	// 採取時有効細胞数
	const M_HARVEST_PRESERVE_VOL	= "harvest_preserve_vol";	// 採取時液量

	// メンバ変数
	public $m_bankid;	// バンクコード
	public $m_bank_no;	// バンク内管理番号
	public $m_receipt_bankid;	// 調整バンクコード
	public $m_receipt_bank_no;	// 調整バンク内管理番号
	public $m_reg_stat;	// 登録状態
	public $m_hla_a1;	// HLA-A(1)
	public $m_hla_a2;	// HLA-A(2)
	public $m_hla_b1;	// HLA-B(1)
	public $m_hla_b2;	// HLA-B(2)
	public $m_hla_cw1;	// HLA-Cw(1)
	public $m_hla_cw2;	// HLA-Cw(2)
	public $m_hla_dr1;	// HLA-DR(1)
	public $m_hla_dr2;	// HLA-DR(2)
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
	public $m_collect_date;	// 採取年月日
	public $m_blood_abo;	// ABO血液型
	public $m_blood_rh;	// Rh血液型
	public $m_sex;	// 性別
	public $m_separate_method;	// 分離方法
	public $m_freezing_method;	// 凍結方法
	public $m_protect_liq;	// 凍害保護液
	public $m_preserve_vol;	// 保存液量
	public $m_preserve_temp;	// 保存温度
	public $m_cell_num;	// 有効細胞数
	public $m_cd34_num;	// CD34細胞数
	public $m_cd34_method;	// CD34測定方法
	public $m_cfu_num;	// CFU総数
	public $m_cfugm_num;	// CFU-GM数
	public $m_cfugm_method;	// CFU測定方法
	public $m_cmvigm_method;	// CMV-IgM検査結果
	public $m_cmvdna_method;	// CMV-DNA検査結果
	public $m_note;	// 備考
	public $m_supply_date;	// 供給年月日
	public $m_supply_hosp;	// 供給病院
	public $m_trans_date;	// 移植年月日
	public $m_trans_flag;	// 移植実施報告
	public $m_hosp_code;	// 施設コード
	public $m_rbc_rate;	// 赤血球率
	public $m_caseid;	// 症例番号
	public $m_trump_hosp_code;	// TRUMP施設コード
	public $m_harvest_cell_num;	// 採取時有効細胞数
	public $m_harvest_preserve_vol;	// 採取時液量

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
		      $this::M_BANKID,
		      $this::M_BANK_NO,
		      $this::M_RECEIPT_BANKID,
		      $this::M_RECEIPT_BANK_NO,
		      $this::M_REG_STAT,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
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
		      $this::M_DQB1_2,
		      $this::M_COLLECT_DATE,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_SEPARATE_METHOD,
		      $this::M_FREEZING_METHOD,
		      $this::M_PROTECT_LIQ,
		      $this::M_PRESERVE_VOL,
		      $this::M_PRESERVE_TEMP,
		      $this::M_CELL_NUM,
		      $this::M_CD34_NUM,
		      $this::M_CD34_METHOD,
		      $this::M_CFU_NUM,
		      $this::M_CFUGM_NUM,
		      $this::M_CFUGM_METHOD,
		      $this::M_CMVIGM_METHOD,
		      $this::M_CMVDNA_METHOD,
		      $this::M_NOTE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_TRANS_DATE,
		      $this::M_TRANS_FLAG,
		      $this::M_HOSP_CODE,
		      $this::M_RBC_RATE,
		      $this::M_CASEID,
		      $this::M_TRUMP_HOSP_CODE,
		      $this::M_HARVEST_CELL_NUM,
		      $this::M_HARVEST_PRESERVE_VOL);
		$this->insert_key = array();

		$this->update_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_BANKID,
		      $this::M_BANK_NO,
		      $this::M_RECEIPT_BANKID,
		      $this::M_RECEIPT_BANK_NO,
		      $this::M_REG_STAT,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
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
		      $this::M_DQB1_2,
		      $this::M_COLLECT_DATE,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_SEPARATE_METHOD,
		      $this::M_FREEZING_METHOD,
		      $this::M_PROTECT_LIQ,
		      $this::M_PRESERVE_VOL,
		      $this::M_PRESERVE_TEMP,
		      $this::M_CELL_NUM,
		      $this::M_CD34_NUM,
		      $this::M_CD34_METHOD,
		      $this::M_CFU_NUM,
		      $this::M_CFUGM_NUM,
		      $this::M_CFUGM_METHOD,
		      $this::M_CMVIGM_METHOD,
		      $this::M_CMVDNA_METHOD,
		      $this::M_NOTE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_TRANS_DATE,
		      $this::M_TRANS_FLAG,
		      $this::M_HOSP_CODE,
		      $this::M_RBC_RATE,
		      $this::M_CASEID,
		      $this::M_TRUMP_HOSP_CODE,
		      $this::M_HARVEST_CELL_NUM,
		      $this::M_HARVEST_PRESERVE_VOL);
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
		      $this::M_BANKID,
		      $this::M_BANK_NO,
		      $this::M_RECEIPT_BANKID,
		      $this::M_RECEIPT_BANK_NO,
		      $this::M_REG_STAT,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
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
		      $this::M_DQB1_2,
		      $this::M_COLLECT_DATE,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_SEPARATE_METHOD,
		      $this::M_FREEZING_METHOD,
		      $this::M_PROTECT_LIQ,
		      $this::M_PRESERVE_VOL,
		      $this::M_PRESERVE_TEMP,
		      $this::M_CELL_NUM,
		      $this::M_CD34_NUM,
		      $this::M_CD34_METHOD,
		      $this::M_CFU_NUM,
		      $this::M_CFUGM_NUM,
		      $this::M_CFUGM_METHOD,
		      $this::M_CMVIGM_METHOD,
		      $this::M_CMVDNA_METHOD,
		      $this::M_NOTE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_TRANS_DATE,
		      $this::M_TRANS_FLAG,
		      $this::M_HOSP_CODE,
		      $this::M_RBC_RATE,
		      $this::M_CASEID,
		      $this::M_TRUMP_HOSP_CODE,
		      $this::M_HARVEST_CELL_NUM,
		      $this::M_HARVEST_PRESERVE_VOL);
		$this->get_key = array($this::M_RECID,
		     $this::M_DEL_FLG);

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
		      $this::M_BANKID,
		      $this::M_BANK_NO,
		      $this::M_RECEIPT_BANKID,
		      $this::M_RECEIPT_BANK_NO,
		      $this::M_REG_STAT,
		      $this::M_HLA_A1,
		      $this::M_HLA_A2,
		      $this::M_HLA_B1,
		      $this::M_HLA_B2,
		      $this::M_HLA_CW1,
		      $this::M_HLA_CW2,
		      $this::M_HLA_DR1,
		      $this::M_HLA_DR2,
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
		      $this::M_DQB1_2,
		      $this::M_COLLECT_DATE,
		      $this::M_BLOOD_ABO,
		      $this::M_BLOOD_RH,
		      $this::M_SEX,
		      $this::M_SEPARATE_METHOD,
		      $this::M_FREEZING_METHOD,
		      $this::M_PROTECT_LIQ,
		      $this::M_PRESERVE_VOL,
		      $this::M_PRESERVE_TEMP,
		      $this::M_CELL_NUM,
		      $this::M_CD34_NUM,
		      $this::M_CD34_METHOD,
		      $this::M_CFU_NUM,
		      $this::M_CFUGM_NUM,
		      $this::M_CFUGM_METHOD,
		      $this::M_CMVIGM_METHOD,
		      $this::M_CMVDNA_METHOD,
		      $this::M_NOTE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_TRANS_DATE,
		      $this::M_TRANS_FLAG,
		      $this::M_HOSP_CODE,
		      $this::M_RBC_RATE,
		      $this::M_CASEID,
		      $this::M_TRUMP_HOSP_CODE,
		      $this::M_HARVEST_CELL_NUM,
		      $this::M_HARVEST_PRESERVE_VOL);
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
