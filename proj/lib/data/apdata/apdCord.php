<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCord.php");
require_once(DBD_DIR . "dbdBank.php");

class apdCord {
	//name
	const   DATA_NAME       = "cord";

	private $m_dbdCord;
	private $m_dbdBank;
	private $m_dbdReceiptBank;
	private $apd_list;

	const APD_RECID			=	"recid";		// RECID
	const APD_REG_DATE		=	"reg_date";		// 作成日
	const APD_REG_USER_ID		=	"reg_user_id";		// 作成ユーザID
	const APD_UPD_DATE		=	"upd_date";		// 更新日
	const APD_UPD_USER_ID		=	"upd_user_id";		// 更新ユーザID
	const APD_BANKID		=	"bankid";		// バンクコード
	const APD_BANK_NAME		=	"bank_name";		// バンク名
	const APD_BANK_NO		=	"bank_no";		// バンク内管理番号
	const APD_RECEIPT_BANKID	=	"receipt_bankid";	// 調整バンクコード
	const APD_RECEIPT_BANK_NAME	=	"receipt_bank_name";	// 調整バンク名
	const APD_RECEIPT_BANK_NO	=	"receipt_bank_no";	// 調整バンク内管理番号
	const APD_REG_STAT		=	"reg_stat";		// 登録状態
	const APD_REG_STAT_NAME		=	"reg_stat_name";	// 登録状態表示名
	const APD_HLA_A1		=	"hla_a1";		// HLA-A(1)
	const APD_HLA_A1_NAME		=	"hla_a1_name";		// HLA-A(1)表示名
	const APD_HLA_A2		=	"hla_a2";		// HLA-A(2)
	const APD_HLA_A2_NAME		=	"hla_a2_name";		// HLA-A(2)表示名
	const APD_HLA_B1		=	"hla_b1";		// HLA-B(1)
	const APD_HLA_B1_NAME		=	"hla_b1_name";		// HLA-B(1)表示名
	const APD_HLA_B2		=	"hla_b2";		// HLA-B(2)
	const APD_HLA_B2_NAME		=	"hla_b2_name";		// HLA-B(2)表示名
	const APD_HLA_CW1		=	"hla_cw1";		// HLA-Cw(1)
	const APD_HLA_CW1_NAME		=	"hla_cw1_name";		// HLA-Cw(1)表示名
	const APD_HLA_CW2		=	"hla_cw2";		// HLA-Cw(2)
	const APD_HLA_CW2_NAME		=	"hla_cw2_name";		// HLA-Cw(2)表示名
	const APD_HLA_DR1		=	"hla_dr1";		// HLA-DR(1)
	const APD_HLA_DR1_NAME		=	"hla_dr1_name";		// HLA-DR(1)表示名
	const APD_HLA_DR2		=	"hla_dr2";		// HLA-DR(2)
	const APD_HLA_DR2_NAME		=	"hla_dr2_name";		// HLA-DR(2)表示名
	const APD_HLA_DQ1		=	"hla_dq1";		// HLA-DQ(1)
	const APD_HLA_DQ1_NAME		=	"hla_dq1_name";		// HLA-DQ(1)表示名
	const APD_HLA_DQ2		=	"hla_dq2";		// HLA-DQ(2)
	const APD_HLA_DQ2_NAME		=	"hla_dq2_name";		// HLA-DQ(2)表示名
	const APD_A_1			=	"a_1";			// A(1)
	const APD_A_1_NAME		=	"a_1_name";		// A(1)表示名
	const APD_A_2			=	"a_2";			// A(2)
	const APD_A_2_NAME		=	"a_2_name";		// A(2)表示名
	const APD_B_1			=	"b_1";			// B(1)
	const APD_B_1_NAME		=	"b_1_name";		// B(1)表示名
	const APD_B_2			=	"b_2";			// B(2)
	const APD_B_2_NAME		=	"b_2_name";		// B(2)表示名
	const APD_C_1			=	"c_1";			// C(1)
	const APD_C_1_NAME		=	"c_1_name";		// C(1)表示名
	const APD_C_2			=	"c_2";			// C(2)
	const APD_C_2_NAME		=	"c_2_name";		// C(2)表示名
	const APD_DRB1_1		=	"drb1_1";		// DRB1(1)
	const APD_DRB1_1_NAME		=	"drb1_1_name";		// DRB1(1)表示名
	const APD_DRB1_2		=	"drb1_2";		// DRB1(2)
	const APD_DRB1_2_NAME		=	"drb1_2_name";		// DRB1(2)表示名
	const APD_DQB1_1		=	"dqb1_1";		// DQB1(1)
	const APD_DQB1_1_NAME		=	"dqb1_1_name";		// DQB1(1)表示名
	const APD_DQB1_2		=	"dqb1_2";		// DQB1(2)
	const APD_DQB1_2_NAME		=	"dqb1_2_name";		// DQB1(2)表示名
	const APD_COLLECT_DATE		=	"collect_date";		// 採取年月日
	const APD_BLOOD_ABO		=	"blood_abo";		// ABO血液型
	const APD_BLOOD_RH		=	"blood_rh";		// Rh血液型
	const APD_SEX			=	"sex";			// 性別
	const APD_SEX_NAME		=	"sex_name";		// 性別表示名
	const APD_SEPARATE_METHOD	=	"separate_method";	// 分離方法
	const APD_FREEZING_METHOD	=	"freezing_method";	// 凍結方法
	const APD_PROTECT_LIQ		=	"protect_liq";		// 凍害保護液
	const APD_PRESERVE_VOL		=	"preserve_vol";		// 保存液量
	const APD_PRESERVE_TEMP		=	"preserve_temp";	// 保存温度
	const APD_CELL_NUM		=	"cell_num";		// 有効細胞数
	const APD_CD34_NUM		=	"cd34_num";		// CD34細胞数
	const APD_CD34_METHOD		=	"cd34_method";		// CD34測定方法
	const APD_CFU_NUM		=	"cfu_num";		// CFU総数
	const APD_CFUGM_NUM		=	"cfugm_num";		// CFU-GM数
	const APD_CFUGM_METHOD		=	"cfugm_method";		// CFU測定方法
	const APD_CMVIGM_METHOD		=	"cmvigm_method";	// CMV-IgM検査結果
	const APD_CMVDNA_METHOD		=	"cmvdna_method";	// CMV-DNA検査結果
	const APD_NOTE			=	"note";			// 備考
	const APD_SUPPLY_DATE		=	"supply_date";		// 供給年月日
	const APD_SUPPLY_HOSP		=	"supply_hosp";		// 供給病院
	const APD_TRANS_DATE		=	"trans_date";		// 移植年月日
	const APD_TRANS_FLAG		=	"trans_flag";		// 移植実施報告
	const APD_HOSP_CODE		=	"hosp_code";		// 施設コード
	const APD_HOSP_NAME		=	"hosp_name";		// 施設名
	const APD_RBC_RATE		=	"rbc_rate";		// 赤血球率
	const APD_CASEID		=	"caseid";		// 症例番号
	const APD_TRUMP_HOSP_CODE	=	"trump_hosp_code";	// TRUMP施設コード
	const APD_TRUMP_HOSP_NAME	=	"trump_hosp_name";	// TRUMP施設名
	const APD_HARVEST_CELL_NUM	=	"harvest_cell_num";	// 採取時有効細胞数
	const APD_HARVEST_PRESERVE_VOL	=	"harvest_preserve_vol";	// 採取時液量

	public function __construct() {
		$this->m_dbdCord = new dbdCord();			// 臍帯血情報
		$this->m_dbdBank = new dbdBank();			// 管理バンク情報
		$this->m_dbdReceiptBank = new dbdBank();		// 調整バンク情報

		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDCord() {
		return $this->m_dbdCord;
	}

	public function getDBDBank() {
		return $this->m_dbdBank;
	}

	public function getDBDReceiptBank() {
		return $this->m_dbdReceiptBank;
	}

	public function convertData($a_list) {
		$this->m_dbdCord->convertData($a_list);
		$this->m_dbdBank->convertData($a_list);
		$this->m_dbdReceiptBank->convertData($a_list);
	}
	
	public function convertGetCordList() {
		$cord_dbo = $this->m_dbdCord->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($cord_dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			$apd_data[$key_preg] = $cord_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectCordList() {
		$cord_list = $this->m_dbdCord->getDBOList();

		$apd_data_count = 0;
		debug_log("cord count = ".count($cord_list));
		for($i = 0; $i < count($cord_list); $i++) {
			$cord_dbo = $cord_list[$i];
	
			$apd_data = array();

			foreach($cord_dbo as $key => $value) {
				$key_preg = preg_replace('/m_/', '', $key);
				$apd_data[$key_preg] = $cord_dbo->{$key};
			}

			$this->apd_list[$apd_data_count] = $apd_data;
			$apd_data_count++;
		}
		
	}
}
?>
