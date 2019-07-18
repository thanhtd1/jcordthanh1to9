<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

class dbdCore {
	public $l_dbo;
	public $l_dbo_list;

	public function setDBO($a_dbo) {
		$this->l_dbo = $a_dbo;
	}

	public function getDBO() {
		return $this->l_dbo;
	}

	public function setDBOList($a_list) {
		$this->l_dbo_list = $a_list;
	}

	public function getDBOList() {
		return $this->l_dbo_list;
	}

	public function setData($a_key, $a_value) {
		$this->l_dbo->{"m_".$a_key} = $a_value;
	}

	public function getData($a_key) {
		return $this->l_dbo->{"m_".$a_key};
	}

	public function convertData($list) {
		foreach($this->l_dbo as $key => $value)
		{
			$key_preg = preg_replace('/m_/', '', $key);
			$this->l_dbo->{$key} = isset($list[$key_preg])?$list[$key_preg]:null ;
//			debug_log("list key_preg = ".$key_preg." value = ".isset($list[$key_preg])?$list[$key_preg]:null);
//			debug_log("dbo value = ".$this->l_dbo->{$key});
		}
		$this->l_dbo->{"m_".$this->l_dbo::M_DEL_FLG} = 0;
	}
}
?>
