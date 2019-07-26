<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank_caseid.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcBank_caseid.php");
require_once(VALID_L4_DIR . "validBank_caseid.php");

class logicBank_caseid {

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

		$l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
	    // End BLOCK B

	    // Start BLOCK C
	    // End BLOCK C
	    // Start BLOCK D
	    // End BLOCK D

	    // Start BLOCK E
		// APD作成
		$l_apdBank_caseid    = new apdBank_caseid();

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
			$l_rtn = $l_svcBank_caseid->createSqlWhere($l_arrWhere, $l_per_where);
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
			$l_rtn = $l_svcBank_caseid->createSqlSort($l_arrSort, $l_per_sort);
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

		$l_bank_caseid_data = array();
		$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_bank_caseid_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	    // End BLOCK E

	    // Start BLOCK F
		// 取得した配列をDBDに設定
		$l_apdBank_caseid->getDBDBank_caseid()->convertListData($l_bank_caseid_data);
		// APDのリストに変換
		$l_apdBank_caseid->convertSelectBank_caseidList();

		$a_out = $l_apdBank_caseid->getData();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);
	    // End BLOCK F

		return $l_rtn;
	}	
    public function getBankid($l_sess, $a_bankid ,&$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
        // Start BLOCK A
            $l_rtn = 0;
    
            // 日付を取得する。
            $l_date = getCurrentDateTime(DATE_TIME_KIND2);
    
		    $l_apdBank_caseid    = new apdBank_caseid();
            $l_dbdBank_caseid = $l_apdBank_caseid->getDBDBank_caseid();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
        // End BLOCK C
    
		$l_valBank_caseid = new validBank_caseid();
		if ( $l_valBank_caseid->validGet_Bankid($a_bankid,$a_err) == -1 )
		{
			debug_log("<< ".API_RET_NG) ;
			$l_db_con->disconnect(DB_NG);
			return ERR_VALIDATE ;
		}
    
        // Start BLOCK D
        // End BLOCK D
    
        // Start BLOCK E
            // ここから下を処理によって作り変える。
            // ここから業務ロジック
            $a_out_apd = new apdBank_caseid();
    
            $l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_BANKID, $a_bankid);
            $l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_DEL_FLG, 0);
    
            $l_bank_caseid_data = array();
			$l_per_where = "bankid = $a_bankid";
			$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_per_where, null, null, $l_bank_caseid_data);
			$l_valBank_caseid = new validBank_caseid();
			if(count($l_bank_caseid_data) > 1){
				$a_err[] = $l_valBank_caseid->err('Data too much', 'Data too much', "Have more than one bank_id = $a_bankid in DB", 4);                
				$l_db_con->disconnect(DB_NG);
                return ERR_DB_TOO_MUCH;			
			}
            elseif (empty($l_bank_caseid_data)) {
				$a_err[] = $l_valBank_caseid->err('Data not found', 'Data not found', "Don't have any data with bank_id = $a_bankid in DB", 4);                
				$l_db_con->disconnect(DB_NG);
                return ERR_DB_NOT_FOUND;
			}
			elseif($l_rtn<0){
				$l_db_con->disconnect(DB_NG);
                return $l_rtn;
			}
            // ここまでが業務ロジック
        // End BLOCK E
    
        // Start BLOCK F
            debug_log("get bank_caseid rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertBank_caseidData($l_bank_caseid_data[0]);
    
            // APDのリストに変換
            $a_out_apd->convertGetBank_caseidList();
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
    
		    $l_apdBank_caseid    = new apdBank_caseid();
            $l_dbdBank_caseid = $l_apdBank_caseid->getDBDBank_caseid();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
		$l_valBank_caseid = new validBank_caseid();
		if ( $l_valBank_caseid->validGet($a_recid,$a_err) == -1 )
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
            $a_out_apd = new apdBank_caseid();
    
            $l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_RECID, $a_recid);
            $l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_DEL_FLG, 0);
    
            $l_bank_caseid_data = array();
			$l_rtn = $l_svcBank_caseid->Get($l_dbh, $l_dbdBank_caseid, $l_bank_caseid_data);
            if ($l_rtn < 0) {
				if($l_rtn == ERR_DB_TOO_MUCH){
					$a_err[] = $l_valBank_caseid->err('Data too much', 'Data too much', "Have more than one recid = $a_recid in DB", 4);
				}
				elseif($l_rtn == ERR_DB_NOT_FOUND){
					$a_err[] = $l_valBank_caseid->err('Data not found', 'Data not found', "Don't have any data with recid = $a_recid in DB", 4);
				}
                $l_db_con->disconnect(DB_NG);
                return $l_rtn;
            }
            // ここまでが業務ロジック
        // End BLOCK E
    
        // Start BLOCK F
            debug_log("get bank_caseid rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertBank_caseidData($l_bank_caseid_data);
    
            // APDのリストに変換
            $a_out_apd->convertGetBank_caseidList();
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
			$l_dbdBank_caseid = $a_in_apd->getDBDBank_caseid();
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
			$l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank_caseid = new validBank_caseid();
			if ( $l_valBank_caseid->validAdd($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
		// End BLOCK C
		
		// Start BLOCK D
			// DB側とのチェック処理を追加
			// 同一ユーザ名が登録されているかのチェック
			$l_bankid = $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_BANKID);
			$l_where = $l_dbdBank_caseid::DBD_BANKID . " = $l_bankid ";
			$l_where .= " and " . $l_dbdBank_caseid::DBD_DEL_FLG . " = 0";
			debug_log("select where = " . $l_where);
	
			$l_ret_list = array();
			// ユーザテーブルを検索
			$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_where, null, null, $l_ret_list);
			debug_log("select ret = " . $l_rtn);
			if ($l_rtn < 0) {
				// データが見つからないエラー以外の場合はエラー終了。
				if ($l_rtn !== ERR_DB_NOT_FOUND) {
					$l_db_con->disconnect(DB_NG);
					return $l_rtn;
				}
			}
			// 同一ユーザ名が登録されている場合はエラー
			if (count($l_ret_list) >= 1) {
				// エラー処理を作成
				$a_err[] = $l_valBank_caseid->err("バンクID", $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_BANKID), "db.duplicate", 4);
				return -1;
			}

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcBank_caseid->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcBank_caseid->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bank_caseidデータの登録
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYSID, $l_sysid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_OPEID, $l_opeid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_MODE, SYS_MODE_INSERT);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_REG_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_REG_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcBank_caseid->Insert($l_dbh, $l_dbdBank_caseid, $l_recid);
			debug_log("Bank_caseid insert = " . $l_rtn);
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
			$l_dbdBank_caseid = $a_in_apd->getDBDBank_caseid();
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
			$l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank_caseid = new validBank_caseid();
			if ( $l_valBank_caseid->validMod($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}

		// DBのデータを取得
		$l_recid = $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_RECID);
		$l_apdSrcBank_caseid = new apdBank_caseid();
		$l_dbdSrcBank_caseid = $l_apdSrcBank_caseid->getDBDBank_caseid();

		$l_dbdSrcBank_caseid->setData($l_dbdBank_caseid::DBD_RECID, $l_recid);
		$l_dbdSrcBank_caseid->setData($l_dbdBank_caseid::DBD_DEL_FLG, 0);

		$l_bank_caseid_data = array();
		$l_rtn = $l_svcBank_caseid->Get($l_dbh, $l_dbdSrcBank_caseid, $l_bank_caseid_data);
		if ($l_rtn < 0) {
			$a_err[] = $l_valBank_caseid->err("RECID", $l_recid, "db.notfound", 4);
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// End BLOCK C
		
		// Start BLOCK D
			// -------------------------------------------------------------------
			// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
			// ------------------------------------------------------------------
			$l_bankid = $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_BANKID);
			if ($l_bankid !== "") {
				$l_where = $l_dbdBank_caseid::DBD_BANKID . " = " . $l_bankid;
				$l_where .= " and " . $l_dbdBank_caseid::DBD_DEL_FLG . " = 0";
				$l_where .= " and " . $l_dbdBank_caseid::DBD_RECID . " = " . $l_recid;

				$l_ret_list = array();
				$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_where, null, null, $l_ret_list);
				if ($l_rtn < 0) {
					$a_err[] = $l_valBank_caseid->err("バンクID", $l_bankid, "db.bankid.notfound", 4);
					$l_db_con->disconnect(DB_NG);
					return $l_rtn;
				}
			}

				// DB側とのチェック処理を追加
			// -------------------------------------------------------------------
			// 同一ユーザ名が登録されているかのチェック
			// -------------------------------------------------------------------
			$l_bankid = $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_BANKID);
			$l_where = $l_dbdBank_caseid::DBD_BANKID . " = '" . $l_bankid . "' ";
			$l_where .= " and " . $l_dbdBank_caseid::DBD_DEL_FLG . " = 0";
			$l_where .= " and " . $l_dbdBank_caseid::DBD_RECID . " != " . $l_recid;

			debug_log("select where = " . $l_where);

			$l_ret_list = array();
			// ユーザテーブルを検索
			$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_where, null, null, $l_ret_list);
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
				$a_err[] = $l_valBank_caseid->err("バンクID", $l_bankid, "db.bankid.duplicate", 4);
				return -1;
			}


		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
 			$l_rtn = $l_svcBank_caseid->getSysId($l_dbh, $l_sysid);
 			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
               			$l_opeid = 0;
 			$l_rtn = $l_svcBank_caseid->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bank_caseidデータの登録
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYSID, $l_sysid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_OPEID, $l_opeid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcBank_caseid->Update($l_dbh, $l_dbdBank_caseid);
			debug_log("Bank_caseid update = " . $l_rtn);
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
			$l_dbdBank_caseid = $a_in_apd->getDBDBank_caseid();
			$l_dbo = $l_dbdBank_caseid->getDBO();
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
			$l_svcBank_caseid = new dbsvcBank_caseid($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank_caseid = new validBank_caseid();
			if ( $l_valBank_caseid->validDel($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
			// DBのデータを取得
			$l_recid = $a_in_apd->getDBDBank_caseid()->getData($l_dbdBank_caseid::DBD_RECID);

			$l_where = $l_dbdBank_caseid::DBD_RECID . " = " . $l_recid;
			$l_ret_list = array();
			$l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if ($l_rtn < 0 && count($l_ret_list) <= 0) {
				$a_err[] = $l_valBank_caseid->err("RECID", $l_recid, "db.notfound", 4);
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		// End BLOCK C
		
		// Start BLOCK D
			// -------------------------------------------------------------------
			// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
			// ------------------------------------------------------------------
		
            $l_where = $l_dbdBank_caseid::DBD_DEL_FLG . " = 1";
            $l_where .= " and " . $l_dbdBank_caseid::DBD_RECID . " = " . $l_recid;

            $l_ret_list = array();
            $l_rtn = $l_svcBank_caseid->Select($l_dbh, $l_where, null, null, $l_ret_list);
            if (count($l_ret_list) >= 1) {
                $a_err[] = $l_valBank_caseid->err("RECID", $l_recid, "db.isdeleted", 4);
                return -1;
            }

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcBank_caseid->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcBank_caseid->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bank_caseidデータの登録
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYSID, $l_sysid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_OPEID, $l_opeid);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_DATE, $l_date);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_UPD_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank_caseid->setData($l_dbdBank_caseid::DBD_DEL_FLG, 1);
	
			$l_rtn = $l_svcBank_caseid->Delete($l_dbh, $l_dbo);
			debug_log("Bank_caseid delete = " . $l_rtn);
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

?>