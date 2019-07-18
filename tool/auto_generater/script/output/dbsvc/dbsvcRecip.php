<?php
require_once(DBSVC_DIR . "dbsvcCore.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBO_DIR . "dboRecip.php");

class dbsvcRecip extends dbsvcCore {
	private $m_db_con;

	static $m_table = "recip";
	static $m_getid_str = "select nextval('recip_recid_seq')" ;

	function __construct($a_db_con) {
		$this->m_db_con = $a_db_con;

		$dbo = new dboRecip();

		// Insert文生成
		$this->m_ins_str = $this->createInsSQL($dbo);
		// Update文生成
		$this->m_upd_str = $this->createUpdSQL($dbo);
		// Delete文生成
		$this->m_del_str = $this->createDelSQL($dbo);
		// HardDelete文生成
		$this->m_hard_del_str = $this->createHardDelSQL($dbo);
		// Get文生成
		$this->m_get_str = $this->createGetSQL($dbo);
		// Select文生成
		$this->m_select_str = $this->createSelectSQL($dbo);
	}

	public function getInsSQL() {
		return $this->m_ins_str;
	}

	public function getUpdSQL() {
		return $this->m_upd_str;
	}

	public function getDelSQL() {
		return $this->m_del_str;
	}

	public function getHardDelSQL() {
		return $this->m_hard_del_str;
	}

	public function getGetSQL() {
		return $this->m_get_str;
	}

	public function getSelectSQL() {
		return $this->m_select_str;
	}

	public function getConn() {
		return $this->m_db_con;
	}

	public function getGetIDSQL() {
		return self::$m_getid_str;
	}

	public function getTableName() {
		return self::$m_table;
	}
}
?>
