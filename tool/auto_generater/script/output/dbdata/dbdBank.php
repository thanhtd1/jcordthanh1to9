<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboBank.php");

class dbdBank extends dbdCore {

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
	const DBD_BANKID	= "bankid";	// バンクID
	const DBD_CUR_BANKID	= "cur_bankid";	// 管理バンクID
	const DBD_AVAILABLE	= "available";	// 有効フラグ
	const DBD_BANK_NAME	= "bank_name";	// バンク名称
	const DBD_SHORT_NAME	= "short_name";	// 省略名称
	const DBD_ENAME	= "ename";	// 英語名称
	const DBD_SHORT_ENAME	= "short_ename";	// 英語省略名称
	const DBD_PERSON	= "person";	// 担当者
	const DBD_TEL_NUM	= "tel_num";	// 電話番号
	const DBD_FAX_NUM	= "fax_num";	// FAX番号
	const DBD_KIND	= "kind";	// バンク種別
	const DBD_ROW_NTH	= "row_nth";	// バンク並び順


	public function __construct() {
		$this->l_dbo = new dboBank();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboBank();
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
