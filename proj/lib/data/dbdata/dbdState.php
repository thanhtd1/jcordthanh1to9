<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboState.php");

class dbdState extends dbdCore {

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
	const DBD_RECIPID	= "recipid";	// 患者ID
	const DBD_CORDID	= "cordid";	// さい帯血ID
	const DBD_REG_STAT	= "reg_stat";	// 状態
	const DBD_USER_ID	= "user_id";	// 更新ユーザID
	const DBD_RESERVE_DATE	= "reserve_date";	// 申込日
	const DBD_CANCEL_DATE	= "cancel_date";	// 取消日
	const DBD_SUPPLY_DATE	= "supply_date";	// 供給年月日
	const DBD_SUPPLY_HOSP	= "supply_hosp";	// 供給病院
	const DBD_HOSP_CODE	= "hosp_code";	// 施設コード
	const DBD_RANK	= "rank";	// 適合ランク
	const DBD_USERID	= "userid";	// 施設ユーザID
	const DBD_COCKTAIL	= "cocktail";	// カクテル移植フラグ
	const DBD_SEARCH_NUMBER	= "search_number";	// 検索数
	const DBD_FIT_NUMBER	= "fit_number";	// 適合数
	const DBD_NOTE	= "note";	// 備考


	public function __construct() {
		$this->l_dbo = new dboState();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboState();
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
