<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdState.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcState.php");
require_once(DBSVC_DIR . "dbsvcCord_bak.php");
require_once(VALID_L4_DIR . "validState.php");

class logicState {

	public function list($l_sess, $a_in_where, $a_in_order, &$a_out, &$a_err, $a_trans = TRANS_ON) {
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

		$l_svcState = new dbsvcState($l_dbh);
	    // End BLOCK B

	    // Start BLOCK C
	    // End BLOCK C
	    // Start BLOCK D
	    // End BLOCK D

	    // Start BLOCK E
		// APD作成
		$l_apdState    = new apdState();

		// 条件組み立て
		$l_per_where = null;
		$l_arrWhere = array();
		$l_arrWhere["del_flg"] = 0;
		if ( isset( $a_in_where ) )
		{
			$l_cnt=0;
			foreach( $a_in_where as $l_k => $l_v )
			{
				if ( $l_k == 'isConverted' )      continue;
				$l_arrWhere[$l_k] = $l_v;
				$l_cnt++;
			}
			$l_rtn = $l_svcState->createSqlWhere($l_arrWhere, $l_per_where);
		}
		debug_log("where :". $l_per_where);

		// ソート組み立て
		$l_per_sort = "";
		$l_arrSort = array();
		if ( isset( $a_in_order ) )
		{
			$l_cnt=0;
			foreach( $a_in_order->sortKey as $l_v )
			{
				$l_arrSort[$l_cnt] = $l_v;
				$l_cnt++;
			}
			$l_rtn = $l_svcState->createSqlSort($l_arrSort, $l_per_sort);
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

		$l_state_data = array();
		$l_rtn = $l_svcState->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_state_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	    // End BLOCK E

	    // Start BLOCK F
		// 取得した配列をDBDに設定
		$l_apdState->getDBDState()->convertListData($l_state_data);
		// APDのリストに変換
		$l_apdState->convertSelectStateList();

		$a_out = $l_apdState->getData();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);
	    // End BLOCK F

		return $l_rtn;
	}
    public function get($l_sess, $a_recid ,&$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
        // Start BLOCK A
            $l_rtn = 0;
    
            // 日付を取得する。
            $l_date = getCurrentDateTime(DATE_TIME_KIND2);
    
		    $l_apdState    = new apdState();
            $l_dbdState = $l_apdState->getDBDState();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcState = new dbsvcState($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
		$l_valState = new validState();
		if ( $l_valState->validGet($a_recid,$a_err) == -1 )
		{
			debug_log("<< ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}
        // End BLOCK C
    
        // Start BLOCK D
        // End BLOCK D
    
        // Start BLOCK E
            // ここから下を処理によって作り変える。
            // ここから業務ロジック
            $a_out_apd = new apdState();
    
            $l_dbdState->setData($l_dbdState::DBD_RECID, $a_recid);
            $l_dbdState->setData($l_dbdState::DBD_DEL_FLG, 0);
    
            $l_state_data = array();
			$l_rtn = $l_svcState->Get($l_dbh, $l_dbdState, $l_state_data);
            if ($l_rtn < 0) {
				if($l_rtn == ERR_DB_TOO_MUCH){
					$a_err[] = $l_valState->err('Data too much','Data too much',"Have more than one recid = $a_recid in DB",4);
				}
				elseif($l_rtn == ERR_DB_NOT_FOUND){
					$a_err[] = $l_valState->err('Data not found','Data not found',"Don't have any data with recid = $a_recid in DB",4);
				}
                $l_db_con->disconnect(DB_NG);
                return $l_rtn;
            }
            // ここまでが業務ロジック
        // End BLOCK E
    
        // Start BLOCK F
            debug_log("get state rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertStateData($l_state_data);
    
            // APDのリストに変換
            $a_out_apd->convertGetStateList();
            // ↑ここまでを処理によって作り変える。
    
            $l_db_con->disconnect(DB_OK);
        // End BLOCK F
    
        return $l_rtn;
	}
	public function add($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		// Start BLOCK A 
			$l_rtn = 0;
	
			// 日付を取得する。
			$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	
			// RECID用変数
			$l_recid = 0;
	
			// DBDを取得
			$l_dbdState = $a_in_apd->getDBDState();
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
			$l_svcState = new dbsvcState($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valState = new validState();
			if ( $l_valState->validAdd($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
		// End BLOCK C
		
		// Start BLOCK D

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcState->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcState->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Stateデータの登録
			$l_dbdState->setData($l_dbdState::DBD_SYSID, $l_sysid);
			$l_dbdState->setData($l_dbdState::DBD_OPEID, $l_opeid);
			$l_dbdState->setData($l_dbdState::DBD_SYS_MODE, SYS_MODE_INSERT);
			$l_dbdState->setData($l_dbdState::DBD_SYS_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdState->setData($l_dbdState::DBD_REG_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_REG_USER_ID, $a_sess['USER_ID']);
			$l_dbdState->setData($l_dbdState::DBD_UPD_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcState->Insert($l_dbh, $l_dbdState, $l_recid);
			debug_log("State insert = " . $l_rtn);
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
	
	public function upd($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		// Start BLOCK A 
			$l_rtn = 0;
	
			// 日付を取得する。
			$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	
			// DBDを取得
			$l_dbdState = $a_in_apd->getDBDState();
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
			$l_svcState = new dbsvcState($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valState = new validState();
			if ( $l_valState->validMod($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}

		// DBのデータを取得
		$l_recid = $a_in_apd->getDBDState()->getData($l_dbdState::DBD_RECID);
		$l_apdSrcState = new apdState();
		$l_dbdSrcState = $l_apdSrcState->getDBDState();

		$l_dbdSrcState->setData($l_dbdState::DBD_RECID, $l_recid);
		$l_dbdSrcState->setData($l_dbdState::DBD_DEL_FLG, 0);

		$l_State_data = array();
		$l_rtn = $l_svcState->Get($l_dbh, $l_dbdSrcState, $l_State_data);
		if ($l_rtn < 0) {
			$a_err[] = $l_valState->err("RECID", $l_recid, "db.notfound", 4);
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// End BLOCK C
		
		// Start BLOCK D
		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
 			$l_rtn = $l_svcState->getSysId($l_dbh, $l_sysid);
 			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
               			$l_opeid = 0;
 			$l_rtn = $l_svcState->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Stateデータの登録
			$l_dbdState->setData($l_dbdState::DBD_SYSID, $l_sysid);
			$l_dbdState->setData($l_dbdState::DBD_OPEID, $l_opeid);
			$l_dbdState->setData($l_dbdState::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdState->setData($l_dbdState::DBD_SYS_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdState->setData($l_dbdState::DBD_UPD_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcState->Update($l_dbh, $l_dbdState);
			debug_log("State update = " . $l_rtn);
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
	public function del($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		// Start BLOCK A 
			$l_rtn = 0;
	
			// 日付を取得する。
			$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	
			// DBDを取得
			$l_dbdState = $a_in_apd->getDBDState();
			$l_dbo = $l_dbdState->getDBO();
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
			$l_svcState = new dbsvcState($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valState = new validState();
			if ( $l_valState->validDel($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
			// DBのデータを取得
			$l_recid = $a_in_apd->getDBDState()->getData($l_dbdState::DBD_RECID);

			$l_where = $l_dbdState::DBD_RECID . " = " . $l_recid;
			$l_ret_list = array();
			$l_rtn = $l_svcState->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if ($l_rtn < 0 && count($l_ret_list) <= 0) {
				$a_err[] = $l_valState->err("RECID", $l_recid, "db.notfound", 4);
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		// End BLOCK C
		
		// Start BLOCK D
			// -------------------------------------------------------------------
			// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
			// ------------------------------------------------------------------
		
			$l_where = $l_dbdState::DBD_DEL_FLG . " = 1";
			$l_where .= " and " . $l_dbdState::DBD_RECID . " = " . $l_recid;

			$l_ret_list = array();
			$l_rtn = $l_svcState->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if (count($l_ret_list) >= 1) {
				$a_err[] = $l_valState->err("RECID", $l_recid, "db.isdeleted", 4);
				return -1;
			}

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcState->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcState->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Stateデータの登録
			$l_dbdState->setData($l_dbdState::DBD_SYSID, $l_sysid);
			$l_dbdState->setData($l_dbdState::DBD_OPEID, $l_opeid);
			$l_dbdState->setData($l_dbdState::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdState->setData($l_dbdState::DBD_SYS_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdState->setData($l_dbdState::DBD_UPD_DATE, $l_date);
			$l_dbdState->setData($l_dbdState::DBD_UPD_USER_ID, $a_sess['USER_ID']);
			$l_dbdState->setData($l_dbdState::DBD_DEL_FLG, 1);
	
			$l_rtn = $l_svcState->Delete($l_dbh, $l_dbo);
			debug_log("State delete = " . $l_rtn);
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
}