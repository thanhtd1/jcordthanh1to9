<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboCord_bak.php");

class dbdCord_bak extends dbdCore {

	// 定数
	const DBD_SYSID	= "sysid";	// システムID
	const DBD_OPEID	= "opeid";	// 操作ID
	const DBD_SYS_MODE	= "sys_mode";	// 操作種別
	const DBD_RECID	= "recid";	// RECID
	const DBD_SYS_DATE	= "sys_date";	// 処理日
	const DBD_SYS_USER_ID	= "sys_user_id";	// 処理ユーザID
	const DBD_REG_DATE	= "reg_date";	// 作成日
	const DBD_REG_USER_ID	= "reg_user_id";	// 作成ユーザID
	const DBD_UPD_DATE	= "upd_date";	// 更新日
	const DBD_UPD_USER_ID	= "upd_user_id";	// 更新ユーザID
	const DBD_DEL_FLG	= "del_flg";	// 削除フラグ
	const DBD_BANKID	= "bankid";	// バンクコード
	const DBD_BANK_NO	= "bank_no";	// バンク内管理番号
	const DBD_RECEIPT_BANKID	= "receipt_bankid";	// 調整バンクコード
	const DBD_RECEIPT_BANK_NO	= "receipt_bank_no";	// 調整バンク内管理番号
	const DBD_REG_STAT	= "reg_stat";	// 登録状態
	const DBD_HLA_A1	= "hla_a1";	// HLA-A(1)
	const DBD_HLA_A2	= "hla_a2";	// HLA-A(2)
	const DBD_HLA_B1	= "hla_b1";	// HLA-B(1)
	const DBD_HLA_B2	= "hla_b2";	// HLA-B(2)
	const DBD_HLA_CW1	= "hla_cw1";	// HLA-Cw(1)
	const DBD_HLA_CW2	= "hla_cw2";	// HLA-Cw(2)
	const DBD_HLA_DR1	= "hla_dr1";	// HLA-DR(1)
	const DBD_HLA_DR2	= "hla_dr2";	// HLA-DR(2)
	const DBD_HLA_DQ1	= "hla_dq1";	// HLA-DQ(1)
	const DBD_HLA_DQ2	= "hla_dq2";	// HLA-DQ(2)
	const DBD_A_1	= "a_1";	// A(1)
	const DBD_A_2	= "a_2";	// A(2)
	const DBD_B_1	= "b_1";	// B(1)
	const DBD_B_2	= "b_2";	// B(2)
	const DBD_C_1	= "c_1";	// C(1)
	const DBD_C_2	= "c_2";	// C(2)
	const DBD_DRB1_1	= "drb1_1";	// DRB1(1)
	const DBD_DRB1_2	= "drb1_2";	// DRB1(2)
	const DBD_DQB1_1	= "dqb1_1";	// DQB1(1)
	const DBD_DQB1_2	= "dqb1_2";	// DQB1(2)
	const DBD_COLLECT_DATE	= "collect_date";	// 採取年月日
	const DBD_BLOOD_ABO	= "blood_abo";	// ABO血液型
	const DBD_BLOOD_RH	= "blood_rh";	// Rh血液型
	const DBD_SEX	= "sex";	// 性別
	const DBD_SEPARATE_METHOD	= "separate_method";	// 分離方法
	const DBD_FREEZING_METHOD	= "freezing_method";	// 凍結方法
	const DBD_PROTECT_LIQ	= "protect_liq";	// 凍害保護液
	const DBD_PRESERVE_VOL	= "preserve_vol";	// 保存液量
	const DBD_PRESERVE_TEMP	= "preserve_temp";	// 保存温度
	const DBD_CELL_NUM	= "cell_num";	// 有効細胞数
	const DBD_CD34_NUM	= "cd34_num";	// CD34細胞数
	const DBD_CD34_METHOD	= "cd34_method";	// CD34測定方法
	const DBD_CFU_NUM	= "cfu_num";	// CFU総数
	const DBD_CFUGM_NUM	= "cfugm_num";	// CFU-GM数
	const DBD_CFUGM_METHOD	= "cfugm_method";	// CFU測定方法
	const DBD_CMVIGM_METHOD	= "cmvigm_method";	// CMV-IgM検査結果
	const DBD_CMVDNA_METHOD	= "cmvdna_method";	// CMV-DNA検査結果
	const DBD_NOTE	= "note";	// 備考
	const DBD_SUPPLY_DATE	= "supply_date";	// 供給年月日
	const DBD_SUPPLY_HOSP	= "supply_hosp";	// 供給病院
	const DBD_TRANS_DATE	= "trans_date";	// 移植年月日
	const DBD_TRANS_FLAG	= "trans_flag";	// 移植実施報告
	const DBD_HOSP_CODE	= "hosp_code";	// 施設コード
	const DBD_RBC_RATE	= "rbc_rate";	// 赤血球率
	const DBD_CASEID	= "caseid";	// 症例番号
	const DBD_TRUMP_HOSP_CODE	= "trump_hosp_code";	// TRUMP施設コード
	const DBD_HARVEST_CELL_NUM	= "harvest_cell_num";	// 採取時有効細胞数
	const DBD_HARVEST_PRESERVE_VOL	= "harvest_preserve_vol";	// 採取時液量


	public function __construct() {
		$this->l_dbo = new dboCord_bak();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboCord_bak();
			foreach($dbo_data as $key => $value)
			{
				$key_preg = preg_replace('/m_/', '', $key);
				$dbo_data->{$key} = isset($data[$key_preg])?$data[$key_preg]:null ;
			}
			$this->l_dbo_list[$i] = $dbo_data;
		}
	}

}
?>
