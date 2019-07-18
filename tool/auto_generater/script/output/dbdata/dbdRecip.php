<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboRecip.php");

class dbdRecip extends dbdCore {

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
	const DBD_BIRTHDAY	= "birthday";	// 生年月日
	const DBD_HLA_A1	= "hla_a1";	// HLA-A(1)
	const DBD_HLA_A2	= "hla_a2";	// HLA-A(2)
	const DBD_HLA_B1	= "hla_b1";	// HLA-B(1)
	const DBD_HLA_B2	= "hla_b2";	// HLA-B(2)
	const DBD_HLA_DR1	= "hla_dr1";	// HLA-DR(1)
	const DBD_HLA_DR2	= "hla_dr2";	// HLA-DR(2)
	const DBD_BLOOD_ABO	= "blood_abo";	// ABO血液型
	const DBD_BLOOD_RH	= "blood_rh";	// Rh血液型
	const DBD_SEX	= "sex";	// 性別
	const DBD_CANCEL_DATE	= "cancel_date";	// 最新取消日
	const DBD_RESERVE_DATE	= "reserve_date";	// 最古申込日
	const DBD_NOTE	= "note";	// 備考
	const DBD_ACCEPT	= "accept";	// 受理フラグ
	const DBD_WEIGHT	= "weight";	// 体重
	const DBD_MATCH_NUM	= "match_num";	// 適合抗原数
	const DBD_RESULT_SORT	= "result_sort";	// 検索結果整列順
	const DBD_RESULT_NUM	= "result_num";	// 検索結果表示数
	const DBD_USERID	= "userid";	// 施設ユーザID
	const DBD_HLA_CW1	= "hla_cw1";	// HLA-Cw(1)
	const DBD_HLA_CW2	= "hla_cw2";	// HLA-Cw(2)
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


	public function __construct() {
		$this->l_dbo = new dboRecip();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboRecip();
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
