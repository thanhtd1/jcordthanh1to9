<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdCord.php");
require_once(DBD_DIR . "dbdCord.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcCord.php");
require_once(VALID_DIR . "validCord.php");

class logicCord {
	private $state_check = array(
		'' => array(0 => 0, 1 => 1),
		0 => array(0 => 0, 1 => 1, 4 => 4, 8 => 8),
		1 => array(0 => 1, 2 => 0, 3 => 3, 8 => 8),
		3 => array(0 => 3, 2 => 0, 8 => 8),
		4 => array(0 => 4, 2 => 0, 3 => 3, 8 => 8),
		8 => array(0 => 8, 1 => 0),
		9 => array(0 => 9, 1 => 1, 10 => 10),
		10 => array(0 => 10)
	);
	// 登録
	public function add($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		$l_rtn = 0;

		// DBへ接続
		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		// 接続情報を取得
		$l_dbh = $l_db_con->getConnection();
		// トランザクションを開始
		$l_db_con->begintran($a_trans);
		
		// ここから下を処理によって作り変える。
		// validate処理
		// 入力チェック
		$l_valCord = new validCord();
		if ( $l_valCord->validAdd($a_in_apd,$a_err) != 1 )
		{
			debug_log("<< ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}

		// DB側のチェック処理を追加
		//

		// RECID用変数
		$l_cord_id = 0;

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);

		// ここから業務ロジック
		$l_svcCord = new dbsvcCord($l_dbh);
		//SYSIDを取得する。
		$l_sysid = 0;
		$l_rtn = $l_svcCord->getSysId($l_dbh, $l_sysid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		//OPEIDを取得する。
		$l_opeid = 0;
		$l_rtn = $l_svcCord->getOpeId($l_dbh, $l_opeid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// Cordデータの登録
		$l_dbdCord = $a_in_apd->getDBDCord();
		$l_dbdCord->setData($l_dbdCord::DBD_SYSID, $l_sysid);
		$l_dbdCord->setData($l_dbdCord::DBD_OPEID, $l_opeid);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_MODE, SYS_MODE_INSERT);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_DATE, $l_date);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord->setData($l_dbdCord::DBD_REG_DATE, $l_date);
		$l_dbdCord->setData($l_dbdCord::DBD_REG_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord->setData($l_dbdCord::DBD_UPD_DATE, $l_date);
		$l_dbdCord->setData($l_dbdCord::DBD_UPD_USER_ID, $a_sess['USER_ID']);

		$l_rtn = $l_svcCord->Insert($l_dbh, $l_dbdCord, $l_cord_id);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);

		$a_out_apd = $a_in_apd;

		return $l_rtn;
	}

	// Cordデータの更新を行う。
	public function upd($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		$l_rtn = 0;

//		$ret_state = 0;
//		$ret = $this->checkCordStatus(9, 1, $ret_state);
//		print_r("ret = ".$ret."\n");
//		print_r("state = ".$ret_state."\n");

		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();

		$l_db_con->begintran($a_trans);

		// ここから下を処理によって作り変える。
		// 入力チェック
		$l_valCord = new validCord();
		if ( $l_valCord->validMod($a_in_apd,$a_err) != 1 )
		{
			debug_log("valid error ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}

		// DB側の存在チェック等の処理を追加する。
		// DBのCORDデータを取得する。
		$l_dbdCord = $a_in_apd->getDBDCord();
		$get_recid = $l_dbdCord->getData($l_dbdCord::DBD_RECID);
		$l_src_apdCord = new apdCord();
		$l_src_dbdCord = $l_src_apdCord->getDBDCord();
		$l_src_dbdCord->setData($l_dbdCord::DBD_RECID, $get_recid);
		$l_src_dbdCord->setData($l_dbdCord::DBD_DEL_FLG, 0);

		$l_svcCord = new dbsvcCord($l_dbh);
		$l_cord_data = array();
		$l_rtn = $l_svcCord->Get($l_dbh, $l_src_dbdCord, $l_cord_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		$l_src_dbdCord->convertData($l_cord_data);

		// DBの登録状態と入力された登録状態をチェックする。
		$l_src_reg_stat = $l_src_dbdCord->getData($l_dbdCord::DBD_REG_STAT);
		$l_dst_reg_stat = $l_dbdCord->getData($l_dbdCord::DBD_REG_STAT);
		debug_log("src reg_stat = ".$l_src_reg_stat." dst reg_stat = ".$l_dst_reg_stat);
		$ret_state = 0;
		$l_rtn = $this->checkCordStatus($l_src_reg_stat, $l_dst_reg_stat, $ret_state);
		debug_log("checkCordStatus ret = ".$l_rtn);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		//

		// ここから業務ロジック
		//SYSIDを取得する。
		$l_sysid = 0;
		$l_rtn = $l_svcCord->getSysId($l_dbh, $l_sysid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		//OPEIDを取得する。
		$l_opeid = 0;
		$l_rtn = $l_svcCord->getOpeId($l_dbh, $l_opeid);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// 日付を取得する。
		$l_date = getCurrentDateTime(DATE_TIME_KIND2);

		// Cordテーブルの更新
		$l_dbdCord->setData($l_dbdCord::DBD_SYSID, $l_sysid);
		$l_dbdCord->setData($l_dbdCord::DBD_OPEID, $l_opeid);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_MODE, SYS_MODE_UPDATE);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_DATE, $l_date);
		$l_dbdCord->setData($l_dbdCord::DBD_SYS_USER_ID, $a_sess['USER_ID']);
		$l_dbdCord->setData($l_dbdCord::DBD_UPD_DATE, $l_date);
		$l_dbdCord->setData($l_dbdCord::DBD_UPD_USER_ID, $a_sess['USER_ID']);

//		$l_rtn = $l_svcCord->Update($l_dbh, $l_dbdCord);
		debug_log("user update = " . $l_rtn);
//		if ($l_rtn < 0) {
//			$l_db_con->disconnect(DB_NG);
//			return $l_rtn;
//		}
		$a_out_apd = $a_in_apd;
		// ここまでが業務ロジック
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);

		return $l_rtn;
	}

	// Cordのrecidからデータを取得する。
	public function get($a_sess, $a_recid ,&$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		$l_rtn = 0;

		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();
		$l_db_con->begintran($a_trans);

		// ここから下を処理によって作り変える。

		// ここから業務ロジック
		$l_apdCord = new apdCord();
		$a_out_apd = new apdCord();

		$l_dbdCord = $l_apdCord->getDBDCord();
		$l_dbdCord->setData($l_dbdCord::DBD_RECID, $a_recid);
		$l_dbdCord->setData($l_dbdCord::DBD_DEL_FLG, 0);

		$l_cord_data = array();
		$l_svcCord = new dbsvcCord($l_dbh);
		$l_rtn = $l_svcCord->Get($l_dbh, $l_dbdCord, $l_cord_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック

		debug_log("get user rtn  = " . $l_rtn);
		$a_out_apd->convertData($l_cord_data);

		// APDのリストに変換
		$a_out_apd->convertGetCordList();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);

		return $l_rtn;
	}

	// 全件取得して、APDのデータ型のリストに変換して返す。
	public function list($a_sess, $a_in_where, $a_in_order, &$a_out, &$a_err, $a_trans = TRANS_ON) {
		$l_rtn = 0;

		$l_db_con = new dbsvcCommon();
		$l_rtn = $l_db_con->connect();

		$l_dbh = $l_db_con->getConnection();
		$l_db_con->begintran($a_trans);

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
		$l_apdCord    = new apdCord();

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

		$l_cord_data = array();
		$l_svcCord = new dbsvcCord($l_dbh);
		$l_rtn = $l_svcCord->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_cord_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック

		$l_apdCord->getDBDCord()->convertListData($l_cord_data);

		$l_apdCord->convertSelectCordList();

		$a_out = $l_apdCord->getData();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);

		return $l_rtn;
	}

	// 登録状態のチェックを行う。
	private function checkCordStatus($src_reg_stat, $dst_reg_stat, &$state) {
		$l_rtn = -1;

		debug_log("src_reg_stat = ". $src_reg_stat);
		debug_log("dst_reg_stat = ". $dst_reg_stat);

		var_dump($this->state_check[$src_reg_stat]);

		foreach ($this->state_check as $key => $arr) {
			if ($key == $src_reg_stat) {
				foreach ($arr as $key2 => $value) {
					if ($key2 === $dst_reg_stat) {
						$state = $value;
						$l_rtn = 0;
						return $l_rtn;
					}
				}
			}
		}

		return $l_rtn;
	}
}

?>
