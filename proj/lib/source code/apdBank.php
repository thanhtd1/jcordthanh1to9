<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdBank.php");

class apdBank {
	//name
	const   DATA_NAME       = "bank";

	private $m_dbdBank;
	private $apd_list;

	public function __construct() {
		$this->m_dbdBank = new dbdBank();
		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDBank() {
		return $this->m_dbdBank;
	}

	public function convertBankData($a_list) {
		$this->m_dbdBank->convertData($a_list);
	}

	public function convertData($a_list) {
		$this->m_dbdBank->convertData($a_list);
	}
	
	public function convertUpdateData($a_list) {
		$this->m_dbdBank->convertData($a_list);
	}

	public function convertGetBankList() {
		$bank_dbo = $this->m_dbdBank->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($bank_dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			$apd_data[$key_preg] = $bank_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectBankList() {
		$bank_list = $this->m_dbdBank->getDBOList();

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
