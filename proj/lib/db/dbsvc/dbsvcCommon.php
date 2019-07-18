<?php

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

class dbsvcCommon {

	private static $m_dbh;
	private static $m_trans_flag;

	//
	public function connect($dbname=DB_NAME,$host=DB_SERVER,$port=DB_PORT,$user=DB_USER,$pass=DB_PASS) {
		$dsn = "pgsql:dbname=".$dbname." host=".$host." port=".$port;

		debug_log("dsn = ".$dsn) ;

		$this::$m_dbh = null;
		try {
			$this::$m_dbh = new PDO($dsn, $user, $pass);
		} catch (PDOException $e){
			print('Error:'.$e->getMessage());
		debug_log("Error:". $e->getMessage()) ;
			die();
		}

	}

	public function disconnect($a_flag = DB_OK) {
		debug_log("disconnect flag = ".$this::$m_trans_flag);
		if ($a_flag === DB_OK) {
			$this->commit();
		}
		else {
			$this->rollback();
		}
		$this::$m_dbh = null;
	}

	public function begintran($a_trans_flag) {
		$this::$m_trans_flag = $a_trans_flag;
		if ($this::$m_trans_flag === TRANS_ON) {
			debug_log("transaction ON");
			$this::$m_dbh->beginTransaction();
		}
	}

	public function commit() {
		debug_log("commit flag = ".$this::$m_trans_flag);
		if ($this::$m_trans_flag === TRANS_ON) {
			debug_log("transaction ON COMMIT");
			$this::$m_dbh->commit();
		}
	}

	public function rollback() {
		debug_log("rollback flag = ".$this::$m_trans_flag);
		if ($this::$m_trans_flag === TRANS_ON) {
			debug_log("transaction ON ROLLBACK");
			$this::$m_dbh->rollback();
		}
	}

	public function getConnection() {
		return $this::$m_dbh;
	}

	public function setConnection($a_dbh) {
		$this::$m_dbh = $a_dbh;
	}
}
?>
