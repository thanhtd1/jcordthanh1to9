<?php
require_once(DBO_DIR . "dboCore.php");

class dboDivision extends dboCore {

	const REF_PERSON_ID	= "ref_person_id";	// 社員ID
	const DIVISION	= "division";	// 所属
	const START_DATE	= "start_date";	// 開始年月日


	private $key_list;
	private $insert_key_list;
	private $update_key_list;
	private $hard_delete_key_list;
	private $get_key_list;

	public function __construct() {
		parent::__construct();

		$this->key_list = array($this::RECID,
$this::REF_PERSON_ID,
$this::DIVISION,
$this::START_DATE);

		$this->insert_key_list = array($this::RECID,
$this::REF_PERSON_ID,
$this::DIVISION,
$this::START_DATE);

		$this->update_key_list = array($this::REF_PERSON_ID,
$this::DIVISION,
$this::START_DATE,
$this::RECID);

		$this->hard_delete_key_list = array($this::RECID);

		$this->get_key_list = array($this::RECID);
	}

	public function getKeyList() {
		return $this->key_list;
	}

	public function setInsertData($v_list) {
		$this->data_list = array();
		for($i = 0; $i < count($this->insert_key_list); $i++) {
			if (array_key_exists($this->insert_key_list[$i], $v_list)) {
				$this->data_list[$this->insert_key_list[$i]] = $v_list[$this->insert_key_list[$i]];
			}
		}
	}

	public function setUpdateData($v_list) {
		$this->data_list = array();
		for($i = 0; $i < count($this->update_key_list); $i++) {
			if (array_key_exists($this->update_key_list[$i], $v_list)) {
				$this->data_list[$this->update_key_list[$i]] = $v_list[$this->update_key_list[$i]];
			}
		}
	}

	public function setHardDeleteData($v_list) {
		$this->data_list = array();
		for($i = 0; $i < count($this->hard_delete_key_list); $i++) {
			if (array_key_exists($this->hard_delete_key_list[$i], $v_list)) {
				$this->data_list[$this->hard_delete_key_list[$i]] = $v_list[$this->hard_delete_key_list[$i]];
			}
		}
	}

	public function setGetData($v_list) {
		$this->data_list = array();
		for($i = 0; $i < count($this->hard_delete_key_list); $i++) {
			if (array_key_exists($this->hard_delete_key_list[$i], $v_list)) {
				$this->data_list[$this->hard_delete_key_list[$i]] = $v_list[$this->hard_delete_key_list[$i]];
			}
		}
	}
}
?>
