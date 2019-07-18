<?php
require_once(DBSVC_DIR . "dbsvcCore.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");

class dbsvcPerson extends dbsvcCore {
	private $m_db_con;

	static $m_ins_str = "insert into person(recid,name,mail,company_name) values(?,?,?,?)" ;
	static $m_upd_str = "update person set name = ?,mail = ?,company_name = ? where recid = ?" ;
	static $m_del_str = "update person set  where recid = ?" ;
	static $m_hard_del_str = "delete from person where recid = ?" ;
	static $m_get_str = "select recid,name,mail,company_name from person where recid = ?" ;
	static $m_select_str = "select recid,name,mail,company_name from person" ;
	static $m_getid_str = "select nextval('person_recid_seq')" ;

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
