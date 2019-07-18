<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdUser.php");
require_once(DBD_DIR . "dbdCord_user.php");
require_once(DBD_DIR . "dbdBank.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcCord_user.php");
require_once(DBSVC_DIR . "dbsvcBank.php");
require_once(VALID_L4_DIR . "validUser.php");
require_once(VALID_L4_OPT_DIR . "validUserOpt.php");

class logicUser {

	// 登録
	public function add($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
	// Start BLOCK A 
		$l_rtn = 0;

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);

		// RECID用変数
		$l_user_id = 0;

		// DBDを取得
		$l_dbdCord_user = $a_in_apd->getDBDUser();
	// End BLOCK A

	// Start BLOCK B
		// DBへ接続
		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		// 接続情報を取得
		$l_dbh = $l_db_con->getConnection();
		// トランザクションを開始
		$l_db_con->begintran($a_trans);
		// DBロジックを作成
		$l_svcUser = new dbsvcCord_user($l_dbh);
		$l_svcBank = new dbsvcBank($l_dbh);
	// End BLOCK B
		
		// ここから下を処理によって作り変える。
	// Start BLOCK C
		// validate処理
		// 入力チェック
		$l_valUser = new validUser();
		if ( $l_valUser->validAdd($a_in_apd,$a_err) != 1 )
		{
			debug_log("<< ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}
	// End BLOCK C
	
	// Start BLOCK D
		// DB側とのチェック処理を追加
		// -------------------------------------------------------------------
		// 同一ユーザ名が登録されているかのチェック
		// -------------------------------------------------------------------
		$l_user_name = $a_in_apd->getDBDUser()->getData($l_dbdCord_user::DBD_USER_NAME);
		$l_where = $l_dbdCord_user::DBD_USER_NAME . " = '" . $l_user_name . "' ";
		$l_where .= " and " . $l_dbdCord_user::DBD_DEL_FLG . " = 0";

		debug_log("select where = " . $l_where);

		$l_ret_list = array();
		// ユーザテーブルを検索
		$l_rtn = $l_svcUser->Select($l_dbh, $l_where, null, null, $l_ret_list);
		debug_log("select ret = " . $l_rtn);
		if ($l_rtn < 0) {
			// データが見つからないエラー以外の場合はエラー終了。
			if ($l_rtn !== ERR_DB_NOT_FOUND) {
				debug_log("ここ？");
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		}
		// 同一ユーザ名が登録されている場合はエラー
		if (count($l_ret_list) >= 1) {
			// エラー処理を作成
			$a_err[] = array("what" => "ユーザ名", "how" => $l_user_name, "why" => "db.user_name.duplicate", "level" => 4);
			return -1;
		}

		// -------------------------------------------------------------------
		// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
		// ------------------------------------------------------------------
		$l_dbdBank = new dbdBank();
		$l_bankid = $a_in_apd->getDBDUser()->getData($l_dbdCord_user::DBD_BANKID);
		if ($l_bankid !== "") {
			$l_where = $l_dbdBank::DBD_BANKID . " = " . $l_bankid;
			$l_where .= " and " . $l_dbdCord_user::DBD_DEL_FLG . " = 0";

			$l_ret_list = array();
			$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if ($l_rtn < 0) {
				$a_err[] = array("what" => "バンクID", "how" => $l_bankid, "why" => "db.bankid.notfound", "level" => 4);
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		}
	// End BLOCK D

	// Start BLOCK E
		// ここから業務ロジック
		//SYSIDを取得する。
		$l_sysid = 0;
		$l_rtn = $l_svcUser->getSysId($l_dbh, $l_sysid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		//OPEIDを取得する。
		$l_opeid = 0;
		$l_rtn = $l_svcUser->getOpeId($l_dbh, $l_opeid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// Userデータの登録
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYSID, $l_sysid);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_OPEID, $l_opeid);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_MODE, SYS_MODE_INSERT);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_REG_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_REG_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_PASS_UPD_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_FLAG, 0);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_TIME, null);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_CNT, 0);

		// id-info
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_ID_INFO, 1);

		$l_rtn = $l_svcUser->Insert($l_dbh, $l_dbdCord_user, $l_user_id);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
	// End BLOCK E
		// ここまでが業務ロジック
		// ↑ここまでを処理によって作り変える。

	// Start BLOCK F
		$l_db_con->disconnect(DB_OK);

		$a_out_apd = $a_in_apd;
	// End BLOCK F
		return $l_rtn;
	}

	// Userデータの更新を行う。
	public function upd($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
	// Start BLOCK A
		$l_rtn = 0;

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);

		// DBDを取得
		$l_dbdCord_user = $a_in_apd->getDBDUser();

		$l_apdSrcUser = new apdUser();
		$l_dbdSrcUser = $l_apdSrcUser->getDBDUser();
	// End BLOCK A

	// Start BLOCK B
		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();

		$l_db_con->begintran($a_trans);
		// DBロジックを作成
		$l_svcUser = new dbsvcCord_user($l_dbh);
	// End BLOCK B

	// Start BLOCK C
		// ここから下を処理によって作り変える。
		// 入力チェック
		$l_valUser = new validUser();
		if ( $l_valUser->validMod($a_in_apd,$a_err) != 1 )
		{
			debug_log("valid error ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}

		// DBのデータを取得
		$l_recid = $a_in_apd->getDBDUser()->getData($l_dbdCord_user::DBD_RECID);
		$l_apdSrcUser = new apdUser();
		$l_dbdSrcUser = $l_apdSrcUser->getDBDUser();

		$l_dbdSrcUser->setData($l_dbdCord_user::DBD_RECID, $l_recid);
		$l_dbdSrcUser->setData($l_dbdCord_user::DBD_DEL_FLG, 0);

		$l_user_data = array();
		$l_rtn = $l_svcUser->Get($l_dbh, $l_dbdSrcUser, $l_user_data);
		if ($l_rtn < 0) {
			$a_err[] = array("what" => "ユーザデータ", "how" => $l_recid, "why" => "db.notfound", "level" => 4);
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// 変換
		$l_apdSrcUser->convertData($l_user_data);

		// Validateチェック(OPTION)を実行
		$l_valUseriOpt = new validUserOpt();
		if ($l_valUseriOpt->validModOpt($l_apdSrcUser, $a_in_apd, $a_err) != 1)
		{
			debug_log("valid opt error ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}
	// End BLOCK C

	// Start BLOCK D
		// DB側とのチェック処理を追加
		// -------------------------------------------------------------------
		// 同一ユーザ名が登録されているかのチェック
		// -------------------------------------------------------------------
		$l_user_name = $a_in_apd->getDBDUser()->getData($l_dbdCord_user::DBD_USER_NAME);
		$l_where = $l_dbdCord_user::DBD_USER_NAME . " = '" . $l_user_name . "' ";
		$l_where .= " and " . $l_dbdCord_user::DBD_DEL_FLG . " = 0";
		$l_where .= " and " . $l_dbdCord_user::DBD_RECID . " != " . $l_recid;

		debug_log("select where = " . $l_where);

		$l_ret_list = array();
		// ユーザテーブルを検索
		$l_rtn = $l_svcUser->Select($l_dbh, $l_where, null, null, $l_ret_list);
		debug_log("select ret = " . $l_rtn);
		if ($l_rtn < 0) {
			// データが見つからないエラー以外の場合はエラー終了。
			if ($l_rtn !== ERR_DB_NOT_FOUND) {
				debug_log("ここ？");
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		}
		// 同一ユーザ名が登録されている場合はエラー
		if (count($l_ret_list) >= 1) {
			// エラー処理を作成
			$a_err[] = array("what" => "ユーザ名", "how" => $l_user_name, "why" => "db.user_name.duplicate", "level" => 4);
			return -1;
		}

		// -------------------------------------------------------------------
		// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
		// ------------------------------------------------------------------
		$l_dbdBank = new dbdBank();
		$l_bankid = $a_in_apd->getDBDUser()->getData($l_dbdCord_user::DBD_BANKID);
		if ($l_bankid !== "") {
			$l_where = $l_dbdBank::DBD_BANKID . " = " . $l_bankid;
			$l_where .= " and " . $l_dbdCord_user::DBD_DEL_FLG . " = 0";

			$l_ret_list = array();
			$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if ($l_rtn < 0) {
				$a_err[] = array("what" => "バンクID", "how" => $l_bankid, "why" => "db.bankid.notfound", "level" => 4);
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		}
	// End BLOCK D

	// Start BLOCK E
		// ここから業務ロジック
		//SYSIDを取得する。
		$l_sysid = 0;
		$l_rtn = $l_svcUser->getSysId($l_dbh, $l_sysid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		//OPEIDを取得する。
		$l_opeid = 0;
		$l_rtn = $l_svcUser->getOpeId($l_dbh, $l_opeid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// Userテーブルの更新
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYSID, $l_sysid);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_OPEID, $l_opeid);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_MODE, SYS_MODE_UPDATE);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_DATE, $l_date);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_PASS_UPD_DATE, $l_date);

		$l_rtn = $l_svcUser->Update($l_dbh, $l_dbdCord_user);
		debug_log("user update = " . $l_rtn);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		$a_out_apd = $a_in_apd;
		// ここまでが業務ロジック
	// End BLOCK E
		// ↑ここまでを処理によって作り変える。

	// Start BLOCK F
		$l_db_con->disconnect(DB_OK);
	// End BLOCK F

		return $l_rtn;
	}

	// Userのrecidからデータを取得する。
	public function get($a_sess, $a_recid ,&$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
	// Start BLOCK A
		$l_rtn = 0;

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);

		$l_apdUser = new apdUser();
		$a_out_apd = new apdUser();
		$l_dbdCord_user = $l_apdUser->getDBDUser();
	// End BLOCK A

	// Start BLOCK B
		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();
		$l_db_con->begintran($a_trans);

		$l_svcUser = new dbsvcCord_user($l_dbh);
	// End BLOCK B

	// Start BLOCK C

	// End BLOCK C

	// Start BLOCK D
	// End BLOCK D

	// Start BLOCK E
		// ここから下を処理によって作り変える。
		// ここから業務ロジック
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_RECID, $a_recid);
		$l_dbdCord_user->setData($l_dbdCord_user::DBD_DEL_FLG, 0);

		$l_user_data = array();
		$l_rtn = $l_svcUser->Get($l_dbh, $l_dbdCord_user, $l_user_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	// End BLOCK E

	// Start BLOCK F
		debug_log("get user rtn  = " . $l_rtn);
		// 取得した配列をDBDに設定
		$a_out_apd->convertUserData($l_user_data);

		// APDのリストに変換
		$a_out_apd->convertGetUserList();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);
	// End BLOCK F

		return $l_rtn;
	}

	// 全件取得して、APDのデータ型のリストに変換して返す。
	public function list($a_sess, $a_in_where, $a_in_order, &$a_out, &$a_err, $a_trans = TRANS_ON) {
	// Start BLOCK A
		$l_rtn = 0;

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	// End BLOCK A

	// Start BLOCK B
		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();
		$l_db_con->begintran($a_trans);

		$l_svcUser = new dbsvcCord_user($l_dbh);
	// End BLOCK B

	// Start BLOCK C
	// End BLOCK C
	// Start BLOCK D
	// End BLOCK D

	// Start BLOCK E
		// ここから下を処理によって作り変える。

		// ここから業務ロジック
		// 検索条件
		foreach( $a_in_where as $l_k => $l_v )
		{
			debug_log("$l_k :". $l_v);
		}

		// paging
		// keys (default asc, ! desc) ext. name  !name
		debug_log("paging sortKey count :". count($a_in_order->sortKey));
		foreach( $a_in_order->sortKey as $l_v )
		{
			debug_log("paging sortKey :". $l_v);
		}
		// (null:asc, !:desc)
		debug_log("paging sortDir :". $a_in_order->sortDir);
		// 表示行数:lines
		debug_log("paging lines   :". $a_in_order->lines);
		// 表示頁数:page
		debug_log("paging page    :". $a_in_order->page);

		// APD作成
		$l_apdUser    = new apdUser();

		// 条件組み立て
		$l_per_where = null;
		if ( isset( $a_in_where ) )
		{
			$l_cnt=0;
			foreach( $a_in_where as $l_k => $l_v )
			{
				if ( $l_k == 'isConverted' )      continue;
				if ( $l_cnt > 0 )
				{
					$l_per_where .= " and ";
				}
				$l_per_where .= " $l_k = '$l_v' ";
				$l_cnt++;
			}
		}
		debug_log("where :". $l_per_where);

		// ソート組み立て
		$l_per_sort = "";
		if ( isset( $a_in_order ) )
		{
			$l_cnt=0;
			foreach( $a_in_order->sortKey as $l_v )
			{
				if ( $l_cnt > 0 )
				{
					$l_per_sort .= " , ";
				}

				if ( substr($l_v,0,1) == '!' )
				{
				$l_per_sort .= " " . substr($l_v,1) . " desc ";
				}
				else
				{
					$l_per_sort .= " $l_v ";
				}
				$l_cnt++;
			}
		}
		debug_log("sort :". $l_per_sort);

		$l_per_other = "";
		// PAGE
		// 表示行数:lines
		if ( isset($a_in_order->lines) )
		{
			$l_per_other       .= " limit " . $a_in_order->lines;
		}
		// 表示頁数:page
		if ( isset($a_in_order->page) && $a_in_order->page > 1 )
		{
			$l_per_other       .= " offset " . ($a_in_order->page-1)*$a_in_order->lines ;
		}
		debug_log("sort :". $l_per_sort);

		$l_user_data = array();
		$l_rtn = $l_svcUser->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_user_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	// End BLOCK E

	// Start BLOCK F
		// 取得した配列をDBDに設定
		$l_apdUser->getDBDUser()->convertListData($l_user_data);
		// APDのリストに変換
		$l_apdUser->convertSelectUserList();

		$a_out = $l_apdUser->getData();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);
	// End BLOCK F

		return $l_rtn;
	}

	// ログイン
	function login($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
	}
}

?>
