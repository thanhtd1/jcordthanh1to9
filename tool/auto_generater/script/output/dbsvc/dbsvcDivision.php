<?php
require_once(DBSVC_DIR . "dbsvcCore.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");

class dbsvcDivision extends dbsvcCore {
	private $m_db_con;

	static $m_ins_str = "insert into division(recid,ref_person_id,division,start_date) values(?,?,?,?)" ;
	static $m_upd_str = "update division set ref_person_id = ?,division = ?,start_date = ? where recid = ?" ;
	static $m_del_str = "update division set  where recid = ?" ;
	static $m_hard_del_str = "delete from division where recid = ?" ;
	static $m_get_str = "select recid,ref_person_id,division,start_date from division where recid = ?" ;
	static $m_select_str = "select recid,ref_person_id,division,start_date from division" ;
	static $m_getid_str = "select nextval('division_recid_seq')" ;

	function __construct($a_db_con) {
		$this->m_db_con = $a_db_con;
	}

	public function getInsSQL() {
		return self::$m_ins_str;
	}

	public function getUpdSQL() {
		return self::$m_upd_str;
	}

	public function getDelSQL() {
		return self::$m_del_str;
	}

	public function getHardDelSQL() {
		return self::$m_hard_del_str;
	}

	public function getGetSQL() {
		return self::$m_get_str;
	}

	public function getSelectSQL() {
		return self::$m_select_str;
	}

	public function getConn() {
		return $this->m_db_con;
	}

	public function getGetIDSQL() {
		return self::$m_getid_str;
	}

}
?>
