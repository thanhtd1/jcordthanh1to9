<?php
require_once(DBO_DIR . "dboCore.php");

class dboBank extends dboCore {

	// メンバ定数
	const M_BANKID	= "bankid";	// バンクID
	const M_CUR_BANKID	= "cur_bankid";	// 管理バンクID
	const M_AVAILABLE	= "available";	// 有効フラグ
	const M_BANK_NAME	= "bank_name";	// バンク名称
	const M_SHORT_NAME	= "short_name";	// 省略名称
	const M_ENAME	= "ename";	// 英語名称
	const M_SHORT_ENAME	= "short_ename";	// 英語省略名称
	const M_PERSON	= "person";	// 担当者
	const M_TEL_NUM	= "tel_num";	// 電話番号
	const M_FAX_NUM	= "fax_num";	// FAX番号
	const M_KIND	= "kind";	// バンク種別
	const M_ROW_NTH	= "row_nth";	// バンク並び順

	// メンバ変数
	public $m_bankid;	// バンクID
	public $m_cur_bankid;	// 管理バンクID
	public $m_available;	// 有効フラグ
	public $m_bank_name;	// バンク名称
	public $m_short_name;	// 省略名称
	public $m_ename;	// 英語名称
	public $m_short_ename;	// 英語省略名称
	public $m_person;	// 担当者
	public $m_tel_num;	// 電話番号
	public $m_fax_num;	// FAX番号
	public $m_kind;	// バンク種別
	public $m_row_nth;	// バンク並び順

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
		      $this::M_CUR_BANKID,
		      $this::M_AVAILABLE,
		      $this::M_BANK_NAME,
		      $this::M_SHORT_NAME,
		      $this::M_ENAME,
		      $this::M_SHORT_ENAME,
		      $this::M_PERSON,
		      $this::M_TEL_NUM,
		      $this::M_FAX_NUM,
		      $this::M_KIND,
		      $this::M_ROW_NTH);
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
		      $this::M_CUR_BANKID,
		      $this::M_AVAILABLE,
		      $this::M_BANK_NAME,
		      $this::M_SHORT_NAME,
		      $this::M_ENAME,
		      $this::M_SHORT_ENAME,
		      $this::M_PERSON,
		      $this::M_TEL_NUM,
		      $this::M_FAX_NUM,
		      $this::M_KIND,
		      $this::M_ROW_NTH);
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
		      $this::M_CUR_BANKID,
		      $this::M_AVAILABLE,
		      $this::M_BANK_NAME,
		      $this::M_SHORT_NAME,
		      $this::M_ENAME,
		      $this::M_SHORT_ENAME,
		      $this::M_PERSON,
		      $this::M_TEL_NUM,
		      $this::M_FAX_NUM,
		      $this::M_KIND,
		      $this::M_ROW_NTH);
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
		      $this::M_CUR_BANKID,
		      $this::M_AVAILABLE,
		      $this::M_BANK_NAME,
		      $this::M_SHORT_NAME,
		      $this::M_ENAME,
		      $this::M_SHORT_ENAME,
		      $this::M_PERSON,
		      $this::M_TEL_NUM,
		      $this::M_FAX_NUM,
		      $this::M_KIND,
		      $this::M_ROW_NTH);
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
