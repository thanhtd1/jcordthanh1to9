<?php
require_once(DBO_DIR . "dboCore.php");

class dboHosp_address extends dboCore {

	// メンバ定数
	const M_CODE	= "code";	// 施設コード
	const M_ORG_NAME	= "org_name";	// 施設名
	const M_DEPT_NAME	= "dept_name";	// 診療科名
	const M_CONTACT_NAME	= "CONTACT_NAME";	// 担当者名
	const M_FAX_NUM	= "fax_num";	// FAX番号
	const M_E_MAIL	= "e_mail";	// E-Mail

	// メンバ変数
	public $m_code;	// 施設コード
	public $m_org_name;	// 施設名
	public $m_dept_name;	// 診療科名
	public $m_CONTACT_NAME;	// 担当者名
	public $m_fax_num;	// FAX番号
	public $m_e_mail;	// E-Mail

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
		      $this::M_CODE,
		      $this::M_ORG_NAME,
		      $this::M_DEPT_NAME,
		      $this::M_CONTACT_NAME,
		      $this::M_FAX_NUM,
		      $this::M_E_MAIL);
		$this->insert_key = array();

		$this->update_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_CODE,
		      $this::M_ORG_NAME,
		      $this::M_DEPT_NAME,
		      $this::M_CONTACT_NAME,
		      $this::M_FAX_NUM,
		      $this::M_E_MAIL);
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
		      $this::M_CODE,
		      $this::M_ORG_NAME,
		      $this::M_DEPT_NAME,
		      $this::M_CONTACT_NAME,
		      $this::M_FAX_NUM,
		      $this::M_E_MAIL);
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
		      $this::M_CODE,
		      $this::M_ORG_NAME,
		      $this::M_DEPT_NAME,
		      $this::M_CONTACT_NAME,
		      $this::M_FAX_NUM,
		      $this::M_E_MAIL);
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
