<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdBank.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcBank.php");
require_once(VALID_L4_DIR . "validBank.php");

class logicBank {

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

		$l_svcBank = new dbsvcBank($l_dbh);
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
		$l_apdBank    = new apdBank();

		// 条件組み立て
		$l_per_where = null;
		if ( isset( $a_in_where ) )
		{
			$l_cnt = 0;
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

		$l_bank_data = array();
		$l_rtn = $l_svcBank->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_bank_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	    // End BLOCK E

	    // Start BLOCK F
		// 取得した配列をDBDに設定
		$l_apdBank->getDBDBank()->convertListData($l_bank_data);
		// APDのリストに変換
		$l_apdBank->convertSelectBankList();

		$a_out = $l_apdBank->getData();
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
    
		    $l_apdBank    = new apdBank();
            $l_dbdBank = $l_apdBank->getDBDBank();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcBank = new dbsvcBank($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
        // End BLOCK C
    
        // Start BLOCK D
        // End BLOCK D
    
        // Start BLOCK E
            // ここから下を処理によって作り変える。
            // ここから業務ロジック
            $a_out_apd = new apdBank();
    
            $l_dbdBank->setData($l_dbdBank::DBD_BANKID, $a_bankid);
            $l_dbdBank->setData($l_dbdBank::DBD_DEL_FLG, 0);
    
            $l_bank_data = array();
			$l_per_where = "bankid = $a_bankid";
			$l_rtn = $l_svcBank->Select($l_dbh, $l_per_where, [], [], $l_bank_data);
			if(count($l_bank_data) > 1){
				$a_err[] = array("what" => 'Data too much', "how" =>'Data too much', "why" => "Have more than one bankid = $a_bankid in DB");                
				$l_db_con->disconnect(DB_NG);
                return ERR_DB_TOO_MUCH;			
			}
            elseif (empty($l_bank_data)) {
				$a_err[] = array("what" => 'Data not found', "how" =>'Data not found', "why" => "Don't have any data with bankid = $a_bankid in DB");                
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
            debug_log("get bank rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertBankData($l_bank_data[0]);
    
            // APDのリストに変換
            $a_out_apd->convertGetBankList();
            // ↑ここまでを処理によって作り変える。
    
            $l_db_con->disconnect(DB_OK);
        // End BLOCK F
    
        return $l_rtn;
    }
    public function getRecId($l_sess, $a_recid ,&$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
        // Start BLOCK A
            $l_rtn = 0;
    
            // 日付を取得する。
            $l_date = getCurrentDateTime(DATE_TIME_KIND2);
    
		    $l_apdBank    = new apdBank();
            $l_dbdBank = $l_apdBank->getDBDBank();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcBank = new dbsvcBank($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
		$l_valBank = new validBank();
		$l_out = [];
		if ( $l_valBank->check_RECID($a_recid,$l_out,$a_err,['required'=>1]) == -1 )
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
            $a_out_apd = new apdBank();
    
            $l_dbdBank->setData($l_dbdBank::DBD_RECID, $a_recid);
            $l_dbdBank->setData($l_dbdBank::DBD_DEL_FLG, 0);
    
            $l_bank_data = array();
            $l_rtn = $l_svcBank->Get($l_dbh, $l_dbdBank, $l_bank_data);
            if ($l_rtn < 0) {
				if($l_rtn == ERR_DB_TOO_MUCH){
					$a_err[] = array("what" => 'Data too much', "how" =>'Data too much', "why" => "Have more than one recid = $a_recid in DB");
				}
				elseif($l_rtn == ERR_DB_NOT_FOUND){
					$a_err[] = array("what" => 'Data not found', "how" =>'Data not found', "why" => "Don't have any data with recid = $a_recid in DB");
				}
                $l_db_con->disconnect(DB_NG);
                return $l_rtn;
            }
            // ここまでが業務ロジック
        // End BLOCK E
    
        // Start BLOCK F
            debug_log("get bank rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertBankData($l_bank_data);
    
            // APDのリストに変換
            $a_out_apd->convertGetBankList();
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
			$l_dbdBank = $a_in_apd->getDBDBank();
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
			$l_svcBank = new dbsvcBank($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank = new validBank();
			if ( $l_valBank->validAdd($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
		// End BLOCK C
		
		// Start BLOCK D
			// DB側とのチェック処理を追加
			// 同一ユーザ名が登録されているかのチェック
			$l_bankid = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_BANKID);
			$l_row = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_ROW_NTH);
			$l_where = $l_dbdBank::DBD_BANKID . " = $l_bankid ";
			$l_where .= " and " . $l_dbdBank::DBD_DEL_FLG . " = 0";
			debug_log("select where = " . $l_where);
	
			$l_ret_list = array();
			// ユーザテーブルを検索
			$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
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
				$a_err[] = array("what" => "バンクID", "how" => $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_BANKID), "why" => "db.duplicate", "level" => 4);
				return -1;
			}

			// check row_ntn 
			if(count($l_ret_list) <= 0) {
				$l_where = $l_dbdBank::DBD_ROW_NTH . " = $l_row ";
				$l_where .= " and " . $l_dbdBank::DBD_DEL_FLG . " = 0";
				debug_log("select where = " . $l_where);

				$l_ret_list = array();

				$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
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
					$a_err[] = array("what" => "バンク並び順", "how" => $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_ROW_NTH), "why" => "db.duplicate", "level" => 4);
					return -1;
				}
			}

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcBank->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcBank->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bankデータの登録
			$l_dbdBank->setData($l_dbdBank::DBD_SYSID, $l_sysid);
			$l_dbdBank->setData($l_dbdBank::DBD_OPEID, $l_opeid);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_MODE, SYS_MODE_INSERT);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank->setData($l_dbdBank::DBD_REG_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_REG_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcBank->Insert($l_dbh, $l_dbdBank, $l_recid);
			debug_log("bank insert = " . $l_rtn);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			
		// End BLOCK E
			// ここまでが業務ロジック
			// ↑ここまでを処理によって作り変える。
	
		// Start BLOCK F
			$l_db_con->disconnect(DB_OK);
	
			// get data
			$this->getRecId($a_sess, $l_recid ,$a_out_apd, $a_err, $a_trans = TRANS_ON);
		// End BLOCK F
			return $l_rtn;
		}
	
	public function upd($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		// Start BLOCK A 
			$l_rtn = 0;
	
			// 日付を取得する。
			$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	
			// DBDを取得
			$l_dbdBank = $a_in_apd->getDBDBank();
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
			$l_svcBank = new dbsvcBank($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank = new validBank();
			if ( $l_valBank->validMod($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}

		// DBのデータを取得
		$l_recid = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_RECID);
		$l_apdSrcBank = new apdBank();
		$l_dbdSrcBank = $l_apdSrcBank->getDBDBank();

		$l_dbdSrcBank->setData($l_dbdBank::DBD_RECID, $l_recid);
		$l_dbdSrcBank->setData($l_dbdBank::DBD_DEL_FLG, 0);

		$l_bank_data = array();
		$l_rtn = $l_svcBank->Get($l_dbh, $l_dbdSrcBank, $l_bank_data);
		if ($l_rtn < 0) {
			$a_err[] = array("what" => "銀行データ", "how" => $l_recid, "why" => "db.notfound", "level" => 4);
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}

		// End BLOCK C
		
		// Start BLOCK D
			// -------------------------------------------------------------------
			// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
			// ------------------------------------------------------------------
			$l_bankid = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_BANKID);
			if ($l_bankid !== "") {
				$l_where = $l_dbdBank::DBD_BANKID . " = " . $l_bankid;
				$l_where .= " and " . $l_dbdBank::DBD_DEL_FLG . " = 0";
				$l_where .= " and " . $l_dbdBank::DBD_RECID . " = " . $l_recid;

				$l_ret_list = array();
				$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
				if ($l_rtn < 0) {
					$a_err[] = array("what" => "バンクID", "how" => $l_bankid, "why" => "db.bankid.notfound", "level" => 4);
					$l_db_con->disconnect(DB_NG);
					return $l_rtn;
				}
			}

				// DB側とのチェック処理を追加
			// -------------------------------------------------------------------
			// 同一ユーザ名が登録されているかのチェック
			// -------------------------------------------------------------------
			$l_row_nth = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_ROW_NTH);
			$l_where = $l_dbdBank::DBD_ROW_NTH . " = '" . $l_row_nth . "' ";
			$l_where .= " and " . $l_dbdBank::DBD_DEL_FLG . " = 0";
			$l_where .= " and " . $l_dbdBank::DBD_RECID . " != " . $l_recid;

			debug_log("select where = " . $l_where);

			$l_ret_list = array();
			// ユーザテーブルを検索
			$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
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
				$a_err[] = array("what" => "ユーザ名", "how" => $l_row_nth, "why" => "db.row_nth.duplicate", "level" => 4);
				return -1;
			}


		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
 			$l_rtn = $l_svcBank->getSysId($l_dbh, $l_sysid);
 			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
               			$l_opeid = 0;
 			$l_rtn = $l_svcBank->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bankデータの登録
			$l_dbdBank->setData($l_dbdBank::DBD_SYSID, $l_sysid);
			$l_dbdBank->setData($l_dbdBank::DBD_OPEID, $l_opeid);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_USER_ID, $a_sess['USER_ID']);

	
			$l_rtn = $l_svcBank->Update($l_dbh, $l_dbdBank);
			debug_log("bank update = " . $l_rtn);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		// End BLOCK E
			// ここまでが業務ロジック
			// ↑ここまでを処理によって作り変える。
	
		// Start BLOCK F
			$l_db_con->disconnect(DB_OK);
	
			//get data
			$this->getRecId($a_sess, $l_recid ,$a_out_apd, $a_err, $a_trans = TRANS_ON);
		// End BLOCK F
			return $l_rtn;
		}
	public function del($a_sess, $a_in_apd, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
		// Start BLOCK A 
			$l_rtn = 0;
	
			// 日付を取得する。
			$l_date = getCurrentDateTime(DATE_TIME_KIND2);
	
			// DBDを取得
			$l_dbdBank = $a_in_apd->getDBDBank();
			$l_dbo = $l_dbdBank->getDBO();
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
			$l_svcBank = new dbsvcBank($l_dbh);
		// End BLOCK B
			
			// ここから下を処理によって作り変える。
		// Start BLOCK C
			// validate処理
			// 入力チェック
			$l_valBank = new validBank();
			if ( $l_valBank->validDel($a_in_apd,$a_err) === -1 )
			{
				debug_log("<< ".API_RET_NG) ;
				$l_db_con->disconnect(DB_NG);
				return ERR_VALIDATE ;
			}
			// DBのデータを取得
			$l_recid = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_RECID);

			$l_where = $l_dbdBank::DBD_RECID . " = " . $l_recid;
			$l_ret_list = array();
			$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
			if ($l_rtn < 0 && count($l_ret_list) <= 0) {
				$a_err[] = array("what" => "銀行データ", "how" => $l_recid, "why" => "db.notfound", "level" => 4);
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		// End BLOCK C
		
		// Start BLOCK D
			// -------------------------------------------------------------------
			// バンクIDが入力されている場合、バンクテーブルの値とチェックを行う。
			// ------------------------------------------------------------------
			$l_bankid = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_BANKID);
			if ($l_bankid !== "") {
				$l_where = $l_dbdBank::DBD_BANKID . " = " . $l_bankid;
				$l_where .= " and " . $l_dbdBank::DBD_DEL_FLG . " = 1";
				$l_where .= " and " . $l_dbdBank::DBD_RECID . " = " . $l_recid;

				$l_ret_list = array();
				$l_rtn = $l_svcBank->Select($l_dbh, $l_where, null, null, $l_ret_list);
				if (count($l_ret_list) >= 1) {
					$a_err[] = array("what" => "銀行データ", "how" => $l_bankid, "why" => "db.isdeleted", "level" => 4);
					$l_db_con->disconnect(DB_NG);
					return $l_rtn;
				}
			}

		// End BLOCK D
	
		// Start BLOCK E
			// ここから業務ロジック
			//SYSIDを取得する。
			$l_sysid = 0;
			$l_rtn = $l_svcBank->getSysId($l_dbh, $l_sysid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
			//OPEIDを取得する。
			$l_opeid = 0;
			$l_rtn = $l_svcBank->getOpeId($l_dbh, $l_opeid);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
	
			// Bankデータの登録
			$l_dbdBank->setData($l_dbdBank::DBD_SYSID, $l_sysid);
			$l_dbdBank->setData($l_dbdBank::DBD_OPEID, $l_opeid);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_MODE, SYS_MODE_UPDATE);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_SYS_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_DATE, $l_date);
			$l_dbdBank->setData($l_dbdBank::DBD_UPD_USER_ID, $a_sess['USER_ID']);
			$l_dbdBank->setData($l_dbdBank::DBD_DEL_FLG, 1);

			$l_del_flag = $a_in_apd->getDBDBank()->getData($l_dbdBank::DBD_DEL_FLG);
	
			$l_rtn = $l_svcBank->Delete($l_dbh, $l_dbo);
			debug_log("bank delete = " . $l_rtn);
			if ($l_rtn < 0) {
				$l_db_con->disconnect(DB_NG);
				return $l_rtn;
			}
		// End BLOCK E
			// ここまでが業務ロジック
			// ↑ここまでを処理によって作り変える。
	
		// Start BLOCK F
			$l_db_con->disconnect(DB_OK);
	
			//get data
			$this->getRecId($a_sess, $l_recid ,$a_out_apd, $a_err, $a_trans = TRANS_ON);
		// End BLOCK F
			return $l_rtn;
		}
}

?>