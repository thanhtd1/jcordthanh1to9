<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(COMM_DIR . "logger.php");
require_once(APD_DIR . "apdSystem.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");
require_once(DBSVC_DIR . "dbsvcSystem.php");
require_once(VALID_L4_DIR . "validSystem.php");

class logicSystem {

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

		$l_svcSystem = new dbsvcSystem($l_dbh);
	// End BLOCK B

	// Start BLOCK C
	// End BLOCK C
	// Start BLOCK D
	// End BLOCK D

	// Start BLOCK E
		// APD作成
		$l_apdSystem    = new apdSystem();

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
			$l_rtn = $l_svcSystem->createSqlWhere($l_arrWhere, $l_per_where);
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
			$l_rtn = $l_svcSystem->createSqlSort($l_arrSort, $l_per_sort);
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

		$l_system_data = array();
		$l_rtn = $l_svcSystem->Select($l_dbh, $l_per_where, $l_per_sort, $l_per_other, $l_system_data);
		if ($l_rtn < 0) {
			$l_db_con->disconnect(DB_NG);
			return $l_rtn;
		}
		// ここまでが業務ロジック
	// End BLOCK E

	// Start BLOCK F
		// 取得した配列をDBDに設定
		$l_apdSystem->getDBDSystem()->convertListData($l_system_data);
		// APDのリストに変換
		$l_apdSystem->convertSelectSystemList();

		$a_out = $l_apdSystem->getData();
		// ↑ここまでを処理によって作り変える。

		$l_db_con->disconnect(DB_OK);
	// End BLOCK F

		return $l_rtn;
	}

    public function getItem($l_sess, $a_item_name, &$a_out_apd, &$a_err, $a_trans = TRANS_ON) {
        // Start BLOCK A
            $l_rtn = 0;
    
            // 日付を取得する。
            $l_date = getCurrentDateTime(DATE_TIME_KIND2);
    
		    $l_apdSystem    = new apdSystem();
            $l_dbdSystem = $l_apdSystem->getDBDSystem();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcSystem = new dbsvcSystem($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
        // End BLOCK C
    
		$l_valSystem = new validSystem();
		if ( $l_valSystem->validGet_item_name($a_item_name, $a_err) == -1 )
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
            $a_out_apd = new apdSystem();
    
            $l_dbdSystem->setData($l_dbdSystem::DBD_ITEM_NAME, $a_item_name);
            $l_dbdSystem->setData($l_dbdSystem::DBD_DEL_FLG, 0);
    
            $l_System_data = array();
			$l_per_where = "item_name = '$a_item_name'";
			$l_rtn = $l_svcSystem->Select($l_dbh, $l_per_where, null, null, $l_System_data);
			$l_valSystem = new validSystem();
			if(count($l_System_data) > 1){
				$a_err[] = $l_valSystem->err('Data too much', 'Data too much', "Have more than one item_name = $a_item_name in DB", 4);                
				$l_db_con->disconnect(DB_NG);
                return ERR_DB_TOO_MUCH;			
			}
            elseif (empty($l_System_data)) {
				$a_err[] = $l_valSystem->err('Data not found', 'Data not found', "Don't have any data with item_name = $a_item_name in DB", 4);                
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
            debug_log("get System rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertSystemData($l_System_data[0]);
    
            // APDのリストに変換
            $a_out_apd->convertGetSystemList();
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
    
		    $l_apdSystem    = new apdSystem();
            $l_dbdSystem = $l_apdSystem->getDBDSystem();
        // End BLOCK A
    
        // Start BLOCK B
            $l_db_con = new dbsvcCommon();
            $l_rtn = $l_db_con->connect();
    
            $l_dbh = $l_db_con->getConnection();
            $l_db_con->begintran($a_trans);
    
            $l_svcSystem = new dbsvcSystem($l_dbh);
        // End BLOCK B
    
        // Start BLOCK C
    
		$l_valSystem = new validSystem();
		if ( $l_valSystem->validGet($a_recid, $a_err) == -1 )
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
            $a_out_apd = new apdSystem();
    
            $l_dbdSystem->setData($l_dbdSystem::DBD_RECID, $a_recid);
            $l_dbdSystem->setData($l_dbdSystem::DBD_DEL_FLG, 0);
    
            $l_System_data = array();
			$l_rtn = $l_svcSystem->Get($l_dbh, $l_dbdSystem, $l_System_data);
            if ($l_rtn < 0) {
				if($l_rtn == ERR_DB_TOO_MUCH){
					$a_err[] = $l_valSystem->err('Data too much', 'Data too much', "Have more than one recid = $a_recid in DB", 4);
				}
				elseif($l_rtn == ERR_DB_NOT_FOUND){
					$a_err[] = $l_valSystem->err('Data not found', 'Data not found', "Don't have any data with recid = $a_recid in DB", 4);
				}
                $l_db_con->disconnect(DB_NG);
                return $l_rtn;
            }
            // ここまでが業務ロジック
        // End BLOCK E
    
        // Start BLOCK F
            debug_log("get System rtn  = " . $l_rtn);
            // 取得した配列をDBDに設定
            $a_out_apd->convertSystemData($l_System_data);
    
            // APDのリストに変換
            $a_out_apd->convertGetSystemList();
            // ↑ここまでを処理によって作り変える。
    
            $l_db_con->disconnect(DB_OK);
        // End BLOCK F
    
        return $l_rtn;
    }
}

?>