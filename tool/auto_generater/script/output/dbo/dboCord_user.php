<?php
require_once(DBO_DIR . "dboCore.php");

class dboCord_user extends dboCore {

	// メンバ定数
	const M_BANKID	= "bankid";	// バンクID
	const M_USER_NAME	= "user_name";	// ユーザ名
	const M_PASSWD	= "passwd";	// パスワード
	const M_ORG_NAME	= "org_name";	// 機関名称
	const M_EMPNAME	= "empname";	// 所属
	const M_PERSON	= "person";	// 担当者
	const M_FURIGANA	= "furigana";	// ふりがな
	const M_TEL_NUM1	= "tel_num1";	// 電話番号
	const M_TEL_NUM2	= "tel_num2";	// 内線番号
	const M_FAX_NUM	= "fax_num";	// FAX番号
	const M_ZIP_CODE	= "zip_code";	// 郵便番号
	const M_ADDRESS1	= "address1";	// 住所
	const M_ADDRESS2	= "address2";	// ビル名等
	const M_E_MAIL1	= "e_mail1";	// E-Mailアドレス
	const M_E_MAIL2	= "e_mail2";	// Mailアドレス2
	const M_E_MAIL3	= "e_mail3";	// Mailアドレス3
	const M_E_MAIL4	= "e_mail4";	// Mailアドレス4
	const M_E_MAIL5	= "e_mail5";	// Mailアドレス5
	const M_KIND	= "kind";	// 種別
	const M_NOTE	= "note";	// 備考
	const M_ID_INFO	= "id_info";	// ユーザ情報
	const M_LOCK_FLAG	= "lock_flag";	// ロックフラグ
	const M_LOCK_TIME	= "lock_time";	// ロック日時
	const M_LOCK_CNT	= "lock_cnt";	// ロック回数
	const M_PASS_UPD_DATE	= "pass_upd_date";	// パスワード更新日

	// メンバ変数
	public $m_bankid;	// バンクID
	public $m_user_name;	// ユーザ名
	public $m_passwd;	// パスワード
	public $m_org_name;	// 機関名称
	public $m_empname;	// 所属
	public $m_person;	// 担当者
	public $m_furigana;	// ふりがな
	public $m_tel_num1;	// 電話番号
	public $m_tel_num2;	// 内線番号
	public $m_fax_num;	// FAX番号
	public $m_zip_code;	// 郵便番号
	public $m_address1;	// 住所
	public $m_address2;	// ビル名等
	public $m_e_mail1;	// E-Mailアドレス
	public $m_e_mail2;	// Mailアドレス2
	public $m_e_mail3;	// Mailアドレス3
	public $m_e_mail4;	// Mailアドレス4
	public $m_e_mail5;	// Mailアドレス5
	public $m_kind;	// 種別
	public $m_note;	// 備考
	public $m_id_info;	// ユーザ情報
	public $m_lock_flag;	// ロックフラグ
	public $m_lock_time;	// ロック日時
	public $m_lock_cnt;	// ロック回数
	public $m_pass_upd_date;	// パスワード更新日

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
		      $this::M_USER_NAME,
		      $this::M_PASSWD,
		      $this::M_ORG_NAME,
		      $this::M_EMPNAME,
		      $this::M_PERSON,
		      $this::M_FURIGANA,
		      $this::M_TEL_NUM1,
		      $this::M_TEL_NUM2,
		      $this::M_FAX_NUM,
		      $this::M_ZIP_CODE,
		      $this::M_ADDRESS1,
		      $this::M_ADDRESS2,
		      $this::M_E_MAIL1,
		      $this::M_E_MAIL2,
		      $this::M_E_MAIL3,
		      $this::M_E_MAIL4,
		      $this::M_E_MAIL5,
		      $this::M_KIND,
		      $this::M_NOTE,
		      $this::M_ID_INFO,
		      $this::M_LOCK_FLAG,
		      $this::M_LOCK_TIME,
		      $this::M_LOCK_CNT,
		      $this::M_PASS_UPD_DATE);
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
		      $this::M_USER_NAME,
		      $this::M_PASSWD,
		      $this::M_ORG_NAME,
		      $this::M_EMPNAME,
		      $this::M_PERSON,
		      $this::M_FURIGANA,
		      $this::M_TEL_NUM1,
		      $this::M_TEL_NUM2,
		      $this::M_FAX_NUM,
		      $this::M_ZIP_CODE,
		      $this::M_ADDRESS1,
		      $this::M_ADDRESS2,
		      $this::M_E_MAIL1,
		      $this::M_E_MAIL2,
		      $this::M_E_MAIL3,
		      $this::M_E_MAIL4,
		      $this::M_E_MAIL5,
		      $this::M_KIND,
		      $this::M_NOTE,
		      $this::M_ID_INFO,
		      $this::M_LOCK_FLAG,
		      $this::M_LOCK_TIME,
		      $this::M_LOCK_CNT,
		      $this::M_PASS_UPD_DATE);
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
		      $this::M_USER_NAME,
		      $this::M_PASSWD,
		      $this::M_ORG_NAME,
		      $this::M_EMPNAME,
		      $this::M_PERSON,
		      $this::M_FURIGANA,
		      $this::M_TEL_NUM1,
		      $this::M_TEL_NUM2,
		      $this::M_FAX_NUM,
		      $this::M_ZIP_CODE,
		      $this::M_ADDRESS1,
		      $this::M_ADDRESS2,
		      $this::M_E_MAIL1,
		      $this::M_E_MAIL2,
		      $this::M_E_MAIL3,
		      $this::M_E_MAIL4,
		      $this::M_E_MAIL5,
		      $this::M_KIND,
		      $this::M_NOTE,
		      $this::M_ID_INFO,
		      $this::M_LOCK_FLAG,
		      $this::M_LOCK_TIME,
		      $this::M_LOCK_CNT,
		      $this::M_PASS_UPD_DATE);
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
		      $this::M_USER_NAME,
		      $this::M_PASSWD,
		      $this::M_ORG_NAME,
		      $this::M_EMPNAME,
		      $this::M_PERSON,
		      $this::M_FURIGANA,
		      $this::M_TEL_NUM1,
		      $this::M_TEL_NUM2,
		      $this::M_FAX_NUM,
		      $this::M_ZIP_CODE,
		      $this::M_ADDRESS1,
		      $this::M_ADDRESS2,
		      $this::M_E_MAIL1,
		      $this::M_E_MAIL2,
		      $this::M_E_MAIL3,
		      $this::M_E_MAIL4,
		      $this::M_E_MAIL5,
		      $this::M_KIND,
		      $this::M_NOTE,
		      $this::M_ID_INFO,
		      $this::M_LOCK_FLAG,
		      $this::M_LOCK_TIME,
		      $this::M_LOCK_CNT,
		      $this::M_PASS_UPD_DATE);
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
