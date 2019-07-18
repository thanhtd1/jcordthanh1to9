<?php
require_once(DBO_DIR . "dboCore.php");

class dboPerson extends dboCore {

	const NAME	= "name";	// 氏名
	const MAIL	= "mail";	// メールアドレス
	const COMPANY_NAME	= "company_name";	// 会社名


	private $key_list;
	private $insert_key_list;
	private $update_key_list;
	private $hard_delete_key_list;
	private $get_key_list;

	public function __construct() {
		parent::__construct();

		$this->key_list = array($this::RECID,
$this::NAME,
$this::MAIL,
$this::COMPANY_NAME);

		$this->insert_key_list = array($this::RECID,
$this::NAME,
$this::MAIL,
$this::COMPANY_NAME);

		$this->update_key_list = array($this::NAME,
$this::MAIL,
$this::COMPANY_NAME,
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
