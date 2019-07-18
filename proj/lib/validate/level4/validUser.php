<?php
//======================================================
//
// 機能名：	サンプルチェック Class.
//
// 機能ID：	validUser.php
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
require_once(VALID_L3_DIR . "BVUser.php");

class validUser extends BVUser
{
	function __construct()
	{
		parent::__construct();
	}

	// User 新規登録データチェック
	function validAdd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDUser();

		// BANKIDのチェック
		$l_out = array();
		$l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_INT_HAN));
		// ユーザ名のチェック
		$l_out = array();
		$l_ret |= $this->check_USER_NAME($l_dbd_p->l_dbo->m_user_name,$l_out,$a_err,array('required' => 1));
		// パスワードのチェック
		$l_out = array();
		$l_ret |= $this->check_PASSWD($l_dbd_p->l_dbo->m_passwd,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// ノーマライズされた値を設定する。
		$l_dbd_p->l_dbo->m_passwd = $l_out[0];
		// 機関名称
		$l_out = array();
		$l_ret |= $this->check_ORG_NAME($l_dbd_p->l_dbo->m_org_name,$l_out,$a_err,array('required' => 0));
		// 所属
		$l_out = array();
		$l_ret |= $this->check_EMPNAME($l_dbd_p->l_dbo->m_empname,$l_out,$a_err,array('required' => 0));
		// 担当者
		$l_out = array();
		$l_ret |= $this->check_PERSON($l_dbd_p->l_dbo->m_person,$l_out,$a_err,array('required' => 1));
		// ふりがな
		$l_out = array();
		$l_ret |= $this->check_FURIGANA($l_dbd_p->l_dbo->m_furigana,$l_out,$a_err,array('required' => 0));
		// 電話番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM1($l_dbd_p->l_dbo->m_tel_num1,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 内線番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM2($l_dbd_p->l_dbo->m_tel_num2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// FAX番号
		$l_out = array();
		$l_ret |= $this->check_FAX_NUM($l_dbd_p->l_dbo->m_fax_num,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 郵便番号
		$l_out = array();
		$l_ret |= $this->check_ZIP_CODE($l_dbd_p->l_dbo->m_zip_code,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 住所
		$l_out = array();
		$l_ret |= $this->check_ADDRESS1($l_dbd_p->l_dbo->m_address1,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// ビル名等
		$l_out = array();
		$l_ret |= $this->check_ADDRESS2($l_dbd_p->l_dbo->m_address2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス1
		$l_out = array();
		$l_ret |= $this->check_E_MAIL1($l_dbd_p->l_dbo->m_e_mail1,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス2
		$l_out = array();
		$l_ret |= $this->check_E_MAIL2($l_dbd_p->l_dbo->m_e_mail2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス3
		$l_out = array();
		$l_ret |= $this->check_E_MAIL3($l_dbd_p->l_dbo->m_e_mail3,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス4
		$l_out = array();
		$l_ret |= $this->check_E_MAIL4($l_dbd_p->l_dbo->m_e_mail4,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス5
		$l_out = array();
		$l_ret |= $this->check_E_MAIL5($l_dbd_p->l_dbo->m_e_mail5,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 種別
		$l_out = array();
		$l_ret |= $this->check_KIND($l_dbd_p->l_dbo->m_kind,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_INT_HAN));
		// ユーザ情報
		$l_out = array();
		$l_ret |= $this->check_ID_INFO($l_dbd_p->l_dbo->m_id_info,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_INT_HAN . self::NMZ_ALPHA_HAN));

		// 関連チェック
		// 種別とバンクIDのチェック
		$l_out = array();
		$l_ret |= $this->rel_kind_bankid($l_dbd_p->l_dbo->m_kind, $l_dbd_p->l_dbo->m_bankid, $l_out, $a_err);

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// User 更新データチェック
	function validMod($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDUser();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid,$l_out,$a_err,array('required' => 1));
		// BANKIDのチェック
		$l_out = array();
		$l_ret |= $this->check_BANKID($l_dbd_p->l_dbo->m_bankid,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_INT_HAN));
		// ユーザ名のチェック
		$l_out = array();
		$l_ret |= $this->check_USER_NAME($l_dbd_p->l_dbo->m_user_name,$l_out,$a_err,array('required' => 1));
		// パスワードのチェック
		$l_out = array();
		$l_ret |= $this->check_PASSWD($l_dbd_p->l_dbo->m_passwd,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 機関名称
		$l_out = array();
		$l_ret |= $this->check_ORG_NAME($l_dbd_p->l_dbo->m_org_name,$l_out,$a_err,array('required' => 0));
		// 所属
		$l_out = array();
		$l_ret |= $this->check_EMPNAME($l_dbd_p->l_dbo->m_empname,$l_out,$a_err,array('required' => 0));
		// 担当者
		$l_out = array();
		$l_ret |= $this->check_PERSON($l_dbd_p->l_dbo->m_person,$l_out,$a_err,array('required' => 1));
		// ふりがな
		$l_out = array();
		$l_ret |= $this->check_FURIGANA($l_dbd_p->l_dbo->m_furigana,$l_out,$a_err,array('required' => 0));
		// 電話番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM1($l_dbd_p->l_dbo->m_tel_num1,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 内線番号
		$l_out = array();
		$l_ret |= $this->check_TEL_NUM2($l_dbd_p->l_dbo->m_tel_num2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// FAX番号
		$l_out = array();
		$l_ret |= $this->check_FAX_NUM($l_dbd_p->l_dbo->m_fax_num,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 郵便番号
		$l_out = array();
		$l_ret |= $this->check_ZIP_CODE($l_dbd_p->l_dbo->m_zip_code,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 住所
		$l_out = array();
		$l_ret |= $this->check_ADDRESS1($l_dbd_p->l_dbo->m_address1,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// ビル名等
		$l_out = array();
		$l_ret |= $this->check_ADDRESS2($l_dbd_p->l_dbo->m_address2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス1
		$l_out = array();
		$l_ret |= $this->check_E_MAIL1($l_dbd_p->l_dbo->m_e_mail1,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス2
		$l_out = array();
		$l_ret |= $this->check_E_MAIL2($l_dbd_p->l_dbo->m_e_mail2,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス3
		$l_out = array();
		$l_ret |= $this->check_E_MAIL3($l_dbd_p->l_dbo->m_e_mail3,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス4
		$l_out = array();
		$l_ret |= $this->check_E_MAIL4($l_dbd_p->l_dbo->m_e_mail4,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// メールアドレス5
		$l_out = array();
		$l_ret |= $this->check_E_MAIL5($l_dbd_p->l_dbo->m_e_mail5,$l_out,$a_err,array('required' => 0, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));
		// 種別
		$l_out = array();
		$l_ret |= $this->check_KIND($l_dbd_p->l_dbo->m_kind,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_INT_HAN));
		// ユーザ情報
		$l_out = array();
		$l_ret |= $this->check_ID_INFO($l_dbd_p->l_dbo->m_id_info,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_INT_HAN . self::NMZ_ALPHA_HAN));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}

	// パスワード変更チェック
	function validModPasswd($a_apd,&$a_err) {
		debug_log(">>");
		$l_ret = self::VALID_OK;

		$l_dbd_p = $a_apd->getDBDUser();

		// RECIDのチェック
		$l_out = array();
		$l_ret |= $this->check_RECID($l_dbd_p->l_dbo->m_recid,$l_out,$a_err,array('required' => 1));
		// パスワードのチェック
		$l_out = array();
		$l_ret |= $this->check_PASSWD($l_dbd_p->l_dbo->m_passwd,$l_out,$a_err,array('required' => 1, 'normalize' => self::NMZ_ALPHA_INT_KIGOU_HAN));

		debug_log("<< ($l_ret)");
		return $l_ret;
	}
} // CLASS-EOF
?>
