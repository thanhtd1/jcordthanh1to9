<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboSystem.php");

class dbdSystem extends dbdCore {

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
	const DBD_AVAILABLE	= "available";	// 有効フラグ
	const DBD_ITEM_NAME	= "item_name";	// 項目名
	const DBD_ITEM_VALUE	= "item_value";	// 項目値
	const DBD_ITEM_NOTE	= "item_note";	// 備考


	public function __construct() {
		$this->l_dbo = new dboSystem();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboSystem();
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
