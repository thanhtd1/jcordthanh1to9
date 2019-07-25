<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdBank_caseid.php");

class apdBank_caseid {
	//name
	const   DATA_NAME       = "bank_caseid";

	private $m_dbdBank_caseid;
	private $apd_list;

	public function __construct() {
		$this->m_dbdBank_caseid = new dbdBank_caseid();
		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDBank_caseid() {
		return $this->m_dbdBank_caseid;
	}

	public function convertBank_caseidData($a_list) {
		$this->m_dbdBank_caseid->convertData($a_list);
	}

	public function convertData($a_list) {
		$this->m_dbdBank_caseid->convertData($a_list);
	}
	
	public function convertUpdateData($a_list) {
		$this->m_dbdBank_caseid->convertData($a_list);
	}

	public function convertGetBank_caseidList() {
		$bank_caseid_dbo = $this->m_dbdBank_caseid->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($bank_caseid_dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			$apd_data[$key_preg] = $bank_caseid_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectBank_caseidList() {
		$bank_caseid_list = $this->m_dbdBank_caseid->getDBOList();

		$apd_data_count = 0;
		debug_log("Bank_caseid count = ".count($bank_caseid_list));
		for($i = 0; $i < count($bank_caseid_list); $i++) {
			$bank_caseid_dbo = $bank_caseid_list[$i];
	
			$apd_data = array();

			foreach($bank_caseid_dbo as $key => $value) {
				$key_preg = preg_replace('/m_/', '', $key);
				$apd_data[$key_preg] = $bank_caseid_dbo->{$key};
			}

			$this->apd_list[$apd_data_count] = $apd_data;
			$apd_data_count++;
		}
		
	}
}
?>
