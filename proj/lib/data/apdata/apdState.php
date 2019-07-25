<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdState.php");

class apdState {
	//name
	const   DATA_NAME       = "state";

	private $m_dbdState;
	private $apd_list;

	public function __construct() {
		$this->m_dbdState = new dbdState();
		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDState() {
		return $this->m_dbdState;
	}

	public function convertStateData($a_list) {
		$this->m_dbdState->convertData($a_list);
	}

	public function convertData($a_list) {
		$this->m_dbdState->convertData($a_list);
	}
	
	public function convertUpdateData($a_list) {
		$this->m_dbdState->convertData($a_list);
	}

	public function convertGetStateList() {
		$bank_dbo = $this->m_dbdState->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($bank_dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			$apd_data[$key_preg] = $bank_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectStateList() {
		$bank_list = $this->m_dbdState->getDBOList();

		$apd_data_count = 0;
		debug_log("bank count = ".count($bank_list));
		for($i = 0; $i < count($bank_list); $i++) {
			$bank_dbo = $bank_list[$i];
	
			$apd_data = array();

			foreach($bank_dbo as $key => $value) {
				$key_preg = preg_replace('/m_/', '', $key);
				$apd_data[$key_preg] = $bank_dbo->{$key};
			}

			$this->apd_list[$apd_data_count] = $apd_data;
			$apd_data_count++;
		}
		
	}
}
?>
