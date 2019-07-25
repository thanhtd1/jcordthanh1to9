<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validSystem.php
// 継承  ：	none
// 概要  ：	Valid System class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdSystem.php");
require_once(DBD_DIR . "dbdSystem.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_L3_DIR . "BVSystem.php");

class validSystem extends BVSystem
{
	function __construct()
	{
		parent::__construct();
	}

    // valid Get
    function validGet($a_recid, &$a_err) {
		debug_log(">>");
		
		$l_out = array();
		$res = $this->check_RECID($a_recid, $l_out, $a_err,['required'=> 1]);
		debug_log("<< ($res)");
		return $res;
    }
	
	// validGet for bank id
    function validGet_item_name($a_item_name, &$a_err){
		debug_log(">>");
		
		$l_out = array();
		$res = $this->check_ITEM_NAME($a_item_name, $l_out, $a_err,['required'=> 1]);
		debug_log("<< ($res)");
		return $res;
	}

} // CLASS-EOF
?>
