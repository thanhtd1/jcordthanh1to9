<?php
require_once(DBO_DIR . "dboCore.php");

class dboState extends dboCore {

	// メンバ定数
	const M_RECIPID	= "recipid";	// 患者ID
	const M_CORDID	= "cordid";	// さい帯血ID
	const M_USER_ID	= "user_id";	// 更新ユーザID
	const M_RESERVE_DATE	= "reserve_date";	// 申込日
	const M_CANCEL_DATE	= "cancel_date";	// 取消日
	const M_SUPPLY_DATE	= "supply_date";	// 供給年月日
	const M_SUPPLY_HOSP	= "supply_hosp";	// 供給病院
	const M_HOSP_CODE	= "hosp_code";	// 施設コード
	const M_RANK	= "rank";	// 適合ランク
	const M_USERID	= "userid";	// 施設ユーザID
	const M_COCKTAIL	= "cocktail";	// カクテル移植フラグ
	const M_SEARCH_NUMBER	= "search_number";	// 検索数
	const M_FIT_NUMBER	= "fit_number";	// 適合数

	// メンバ変数
	public $m_recipid;	// 患者ID
	public $m_cordid;	// さい帯血ID
	public $m_user_id;	// 更新ユーザID
	public $m_reserve_date;	// 申込日
	public $m_cancel_date;	// 取消日
	public $m_supply_date;	// 供給年月日
	public $m_supply_hosp;	// 供給病院
	public $m_hosp_code;	// 施設コード
	public $m_rank;	// 適合ランク
	public $m_userid;	// 施設ユーザID
	public $m_cocktail;	// カクテル移植フラグ
	public $m_search_number;	// 検索数
	public $m_fit_number;	// 適合数

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

		$this->m_del_flg = 0;

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
		      $this::M_RECIPID,
		      $this::M_CORDID,
		      $this::M_USER_ID,
		      $this::M_RESERVE_DATE,
		      $this::M_CANCEL_DATE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_HOSP_CODE,
		      $this::M_RANK,
		      $this::M_USERID,
		      $this::M_COCKTAIL,
		      $this::M_SEARCH_NUMBER,
		      $this::M_FIT_NUMBER);
		$this->insert_key = array();

		$this->update_item = array($this::M_SYSID,
		      $this::M_OPEID,
		      $this::M_SYS_MODE,
		      $this::M_SYS_DATE,
		      $this::M_SYS_USER_ID,
		      $this::M_UPD_DATE,
		      $this::M_UPD_USER_ID,
		      $this::M_DEL_FLG,
		      $this::M_RECIPID,
		      $this::M_CORDID,
		      $this::M_USER_ID,
		      $this::M_RESERVE_DATE,
		      $this::M_CANCEL_DATE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_HOSP_CODE,
		      $this::M_RANK,
		      $this::M_USERID,
		      $this::M_COCKTAIL,
		      $this::M_SEARCH_NUMBER,
		      $this::M_FIT_NUMBER);
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
		      $this::M_RECIPID,
		      $this::M_CORDID,
		      $this::M_USER_ID,
		      $this::M_RESERVE_DATE,
		      $this::M_CANCEL_DATE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_HOSP_CODE,
		      $this::M_RANK,
		      $this::M_USERID,
		      $this::M_COCKTAIL,
		      $this::M_SEARCH_NUMBER,
		      $this::M_FIT_NUMBER);
		$this->get_key = array($this::M_RECID);

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
		      $this::M_RECIPID,
		      $this::M_CORDID,
		      $this::M_USER_ID,
		      $this::M_RESERVE_DATE,
		      $this::M_CANCEL_DATE,
		      $this::M_SUPPLY_DATE,
		      $this::M_SUPPLY_HOSP,
		      $this::M_HOSP_CODE,
		      $this::M_RANK,
		      $this::M_USERID,
		      $this::M_COCKTAIL,
		      $this::M_SEARCH_NUMBER,
		      $this::M_FIT_NUMBER);
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
