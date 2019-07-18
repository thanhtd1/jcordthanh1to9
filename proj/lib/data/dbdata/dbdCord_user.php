<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCore.php");
require_once(DBO_DIR . "dboCord_user.php");

class dbdCord_user extends dbdCore {

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
	const DBD_USER_NAME	= "user_name";	// ユーザ名
	const DBD_PASSWD	= "passwd";	// パスワード
	const DBD_ORG_NAME	= "org_name";	// 機関名称
	const DBD_EMPNAME	= "empname";	// 所属
	const DBD_PERSON	= "person";	// 担当者
	const DBD_FURIGANA	= "furigana";	// ふりがな
	const DBD_TEL_NUM1	= "tel_num1";	// 電話番号
	const DBD_TEL_NUM2	= "tel_num2";	// 内線番号
	const DBD_FAX_NUM	= "fax_num";	// FAX番号
	const DBD_ZIP_CODE	= "zip_code";	// 郵便番号
	const DBD_ADDRESS1	= "address1";	// 住所
	const DBD_ADDRESS2	= "address2";	// ビル名等
	const DBD_E_MAIL1	= "e_mail1";	// E-Mailアドレス
	const DBD_E_MAIL2	= "e_mail2";	// Mailアドレス2
	const DBD_E_MAIL3	= "e_mail3";	// Mailアドレス3
	const DBD_E_MAIL4	= "e_mail4";	// Mailアドレス4
	const DBD_E_MAIL5	= "e_mail5";	// Mailアドレス5
	const DBD_KIND	= "kind";	// 種別
	const DBD_NOTE	= "note";	// 備考
	const DBD_ID_INFO	= "id_info";	// ユーザ情報
	const DBD_LOCK_FLAG	= "lock_flag";	// ロックフラグ
	const DBD_LOCK_TIME	= "lock_time";	// ロック日時
	const DBD_LOCK_CNT	= "lock_cnt";	// ロック回数
	const DBD_PASS_UPD_DATE	= "pass_upd_date";	// パスワード更新日


	public function __construct() {
		$this->l_dbo = new dboCord_user();
		$this->l_dbo_list = array();
	}

	public function convertListData($list) {
		$this->l_dbo_list = array();
		for($i = 0; $i < count($list); $i++) {
			$data = $list[$i];
			$dbo_data = new dboCord_user();
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
