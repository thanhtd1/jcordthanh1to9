<?php

class dboCore {

	// メンバ定数
	const M_SYSID	= "sysid";	// システムID
	const M_OPEID	= "opeid";	// 操作ID
	const M_SYS_MODE	= "sys_mode";	// 操作種別
	const M_RECID	= "recid";	// RECID
	const M_SYS_DATE	= "sys_date";	// 処理日
	const M_SYS_USER_ID	= "sys_user_id";	// 処理ユーザID
	const M_REG_DATE	= "reg_date";	// 作成日
	const M_REG_USER_ID	= "reg_user_id";	// 作成ユーザID
	const M_UPD_DATE	= "upd_date";	// 更新日
	const M_UPD_USER_ID	= "upd_user_id";	// 更新ユーザID
	const M_DEL_FLG	= "del_flg";	// 削除フラグ

	// メンバ変数
	public $m_sysid;	// システムID
	public $m_opeid;	// 操作ID
	public $m_sys_mode;	// 操作種別
	public $m_recid;	// RECID
	public $m_sys_date;	// 処理日
	public $m_sys_user_id;	// 処理ユーザID
	public $m_reg_date;	// 作成日
	public $m_reg_user_id;	// 作成ユーザID
	public $m_upd_date;	// 更新日
	public $m_upd_user_id;	// 更新ユーザID
	public $m_del_flg;	// 削除フラグ


	function __construct()
	{
	}
}
?>
