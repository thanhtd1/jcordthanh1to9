<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdSystem.php");

class apdSystem {
	//name
	const   DATA_NAME       = "system";

	private $m_dbdSystem;
	private $apd_list;

	public function __construct() {
		$this->m_dbdSystem = new dbdSystem();
		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDSystem() {
		return $this->m_dbdSystem;
	}

	public function convertSystemData($a_list) {
		$this->m_dbdSystem->convertData($a_list);
	}

	public function convertData($a_list) {
		$this->m_dbdSystem->convertData($a_list);
	}
	
	public function convertUpdateData($a_list) {
		$this->m_dbdSystem->convertData($a_list);
	}

	public function convertGetSystemList() {
		$system_dbo = $this->m_dbdSystem->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($system_dbo as $key => $value) {
			// $key_preg = preg_replace('/m_/', '', $key);
			if(substr($key, 0, 2) == 'm_'){
				$key_preg = substr($key, 2);
			}
			$apd_data[$key_preg] = $system_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectSystemList() {
		$system_list = $this->m_dbdSystem->getDBOList();

		$apd_data_count = 0;
		debug_log("system count = ".count($system_list));
		for($i = 0; $i < count($system_list); $i++) {
			$system_dbo = $system_list[$i];
	
			$apd_data = array();

			foreach($system_dbo as $key => $value) {
				// $key_preg = preg_replace('/m_/', '', $key);
				
				if(substr($key, 0, 2) == 'm_'){
					$key_preg = substr($key, 2);
				}
				$apd_data[$key_preg] = $system_dbo->{$key};
			}

			$this->apd_list[$apd_data_count] = $apd_data;
			$apd_data_count++;
		}
		
	}
}
?>
