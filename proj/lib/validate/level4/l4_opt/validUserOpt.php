<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validUserOpt.php
// 継承  ：	none
// 概要  ：	Valid User class.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdUser.php");
require_once(DBD_DIR . "dbdCord_user.php");

require_once(VALID_DIR . "Valid.php");
require_once(VALID_DIR . "ErrorInfo.php");
require_once(VALID_DIR . "Reason.php");
require_once(VALID_L3_DIR . "BVUser.php");

class validUserOpt extends BVUser
{
	function __construct()
	{
		parent::__construct();
	}

	// 
	function validModOpt($a_src_apd, $a_dst_apd, &$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;
		$l_err_info = new ErrorInfo();

		$l_src_dbd = $a_src_apd->getDBDUser();
		$l_dst_dbd = $a_dst_apd->getDBDUser();

		$l_ret |= $this->cheanged_USER_NAME($l_src_dbd->l_dbo->m_user_name, $l_dst_dbd->l_dbo->m_user_name, "ユーザ名", $l_err_info);

		debug_log("<< ($l_ret)");
		return $l_ret;		
	}

	// ユーザ名が変更されているかをチェックする。
	private function cheanged_USER_NAME($a_src_user_name, $a_dst_user_name, $a_fieldName, $a_errorInfo) {
		debug_log(">>");

		$l_ret = self::VALID_OK;

		if ($a_src_user_name !== $a_dst_user_name) {
			$a_errorInfo->addError($a_fieldName, $a_src_user_name.",".$a_dst_user_name, PVType::rootErrorMsg . ".user_name.changed");
			$l_ret = -1;
			return $l_ret;
		}

		debug_log("<< ($l_ret)");
		return $l_ret;
	}
} // CLASS-EOF
?>
