<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(DBD_DIR . "dbdCord_user.php");

class apdUser {
	//name
	const   DATA_NAME       = "user";

	private $m_dbdCord_user;
	private $apd_list;

	public function __construct() {
		$this->m_dbdCord_user = new dbdCord_user();
		$this->apd_list = array();
	}

	public function setData($a_data) {
		$this->apd_list = $a_data;
	}

	public function getData() {
		return $this->apd_list;
	}

	public function getDBDUser() {
		return $this->m_dbdCord_user;
	}

	public function convertUserData($a_list) {
		$this->m_dbdCord_user->convertData($a_list);
	}

	public function convertData($a_list) {
		$this->m_dbdCord_user->convertData($a_list);
	}
	
	public function convertUpdateData($a_list) {
		$this->m_dbdCord_user->convertData($a_list);
	}

	public function convertGetUserList() {
		$user_dbo = $this->m_dbdCord_user->getDBO();

		$apd_data_count = 0;
		$apd_data = array();

		foreach($user_dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			$apd_data[$key_preg] = $user_dbo->{$key};
		}

		$this->apd_list[$apd_data_count] = $apd_data;
		$apd_data_count++;
	}

	public function convertSelectUserList() {
		$user_list = $this->m_dbdCord_user->getDBOList();

		$apd_data_count = 0;
		debug_log("user count = ".count($user_list));
		for($i = 0; $i < count($user_list); $i++) {
			$user_dbo = $user_list[$i];
	
			$apd_data = array();

			foreach($user_dbo as $key => $value) {
				$key_preg = preg_replace('/m_/', '', $key);
				$apd_data[$key_preg] = $user_dbo->{$key};
			}

			$this->apd_list[$apd_data_count] = $apd_data;
			$apd_data_count++;
		}
		
	}
}
?>
