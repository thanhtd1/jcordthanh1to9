<?php
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "logger.php");

abstract class dbsvcCore {
	// MYSQLのエラーコード
	const SQLSTATE_DUPLICATE = 23000;

	private $m_ins_str;
	private $m_upd_str;
	private $m_del_str;
	private $m_hard_del_str;
	private $m_get_str;
	private $m_select_str;

	// Insert文生成
	public function createInsSQL($dbo, $item_data = null) {
		$table = $this->getTableName();

		if (is_null($item_data)) {
			$item_data = $dbo->getInsertItem();
		}

		$sql = "insert into ".$table."(";
		$values = " values(";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $item_data) !== false) {
				$sql .= $key_preg . ",";
				$values .= "?,";
			}
		}
		$sql = rtrim($sql, ',');
		$values = rtrim($values, ',');
		$sql .= ")" . $values . ")";

		return $sql;
	}

	// Update文生成
	public function createUpdSQL($dbo, $item_data = null, $key_data = null) {
		$table = $this->getTableName();

		if (is_null($item_data)) {
			$item_data = $dbo->getUpdateItem();
		}

		if (is_null($key_data)) {
			$key_data = $dbo->getUpdateKey();
		}

		$sql = "update ".$table." set ";
		$key_count = 0;
		$where = " where ";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $item_data) !== false) {
				$sql .= $key_preg . " = ?,";
			}
			if (array_search($key_preg, $key_data) !== false) {
				if ($key_count === 0) {
					$where .= $key_preg . " = ?";
				}
				else {
					$where .= " and " . $key_preg . " = ?";
				}
				$key_count++;
			}
		}
		$sql = rtrim($sql, ',');
		$sql .= $where;

		return $sql;
	}

	// Delete文生成
	public function createDelSQL($dbo, $item_data = null, $key_data = null) {
		$table = $this->getTableName();

		if (is_null($item_data)) {
			$item_data = $dbo->getDeleteItem();
		}

		if (is_null($key_data)) {
			$key_data = $dbo->getDeleteKey();
		}

		$sql = "update ".$table." set ";
		$key_count = 0;
		$where = " where ";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $item_data) !== false) {
				$sql .= $key_preg . " = ?,";
			}
			if (array_search($key_preg, $key_data) !== false) {
				if ($key_count === 0) {
					$where .= $key_preg . " = ?";
				}
				else {
					$where .= " and " . $key_preg . " = ?";
				}
				$key_count++;
			}
		}
		$sql = rtrim($sql, ',');
		$sql .= $where;

		return $sql;
	}

	// HardDelete文生成
	public function createHardDelSQL($dbo, $key_data = null) {
		$table = $this->getTableName();

		if (is_null($key_data)) {
			$key_data = $dbo->getHardDeleteKey();
		}

		$sql = "delete from ".$table." ";
		$key_count = 0;
		$where = " where ";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $dbo->getHardDeleteKey()) !== false) {
				if ($key_count === 0) {
					$where .= $key_preg . " = ?";
				}
				else {
					$where .= " and " . $key_preg . " = ?";
				}
				$key_count++;
			}
		}
		$sql .= $where;

		return $sql;
	}

	// Get文生成
	public function createGetSQL($dbo, $item_data = null, $key_data = null) {
		$table = $this->getTableName();

		if (is_null($item_data)) {
			$item_data = $dbo->getGetItem();
		}

		if (is_null($key_data)) {
			$key_data = $dbo->getGetKey();
		}

		$sql = "select ";
		$key_count = 0;
		$where = " where ";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $item_data) !== false) {
				$sql .= $key_preg . ",";
			}
			if (array_search($key_preg, $key_data) !== false) {
				if ($key_count === 0) {
					$where .= $key_preg . " = ?";
				}
				else {
					$where .= " and " . $key_preg . " = ?";
				}
				$key_count++;
			}
		}
		$sql = rtrim($sql, ',');
		$sql .= " from ".$table." " . $where;

		return $sql;
	}

	// Select文生成
	public function createSelectSQL($dbo, $item_data = null) {
		$table = $this->getTableName();

		if (is_null($item_data)) {
			$item_data = $dbo->getSelectItem();
		}

		$sql = "select ";
		foreach($dbo as $key => $value) {
			$key_preg = preg_replace('/m_/', '', $key);
			if (array_search($key_preg, $item_data) !== false) {
				$sql .= $key_preg . ",";
			}
		}
		$sql = rtrim($sql, ',');
		$sql .= " from ".$table." ";

		return $sql;
	}

	// 登録処理
	public function Insert($dbh, $dbdata, &$recid) {
		$ret = 0;
		try {
			$dbo = $dbdata->getDBO();

			$strSQL = $this->getInsSQL();
			$sth = $dbh->prepare($strSQL);

			$item_data = $dbo->getInsertItem();
			$key_data = $dbo->getInsertKey();

			// RECIDを取得する。
			$ret = $this->GetID($dbh, $recid);
			if ($ret < 0) {
				return $ret;
			}
			$dbo->m_recid = $recid;
			debug_log("get recid = ". $recid);

			$ret = $this->bindSQL($sth, $dbo, $item_data, $key_data);
			debug_log("bindSQL: ret($ret)") ;
			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
				$ret = ERR_DB_INSERT;
			}
		}
		catch (Exception $e) {
			if ($e->getCode() == SQLSTATE_DUPLICATE) {
				$ret = ERR_DB_DUPLICATE;
			}
			else {
				print_r('code = '.$e->getCode().'  message = '.$e->getMessage()."\n");
				$ret = ERR_DB_INSERT;
			}
		}

		return $ret;
	}

	// 更新処理
	public function Update($dbh, $dbdata) {
		$ret = 0;
		debug_log(">>") ;

		try {
			$dbo = $dbdata->getDBO();

			$strSQL = $this->getUpdSQL();
			$sth = $dbh->prepare($strSQL);

			$item_data = $dbo->getUpdateItem();
			$key_data = $dbo->getUpdateKey();

			$ret = $this->bindSQL($sth, $dbo, $item_data, $key_data);
			debug_log("bindSQL: ret($ret)") ;
			$ret = $sth->execute() ;
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
				$ret = ERR_DB_UPDATE;
			}
		}
		catch (Exception $e) {
			debug_log("Exception:$e") ;
			if ($e->getCode() == SQLSTATE_DUPLICATE) {
				$ret = ERR_DB_DUPLICATE;
			}
			else {
				print_r('code = '.$e->getCode().'  message = '.$e->getMessage()."\n");
				$ret = ERR_DB_UPDATE;
			}
		}

		debug_log("<< ($ret)") ;
		return $ret;
	}

	// 削除処理(論理)
	public function Delete($dbh, $dbo) {
		$ret = 0;
		try {
			$strSQL = $this->getDelSQL();
			$sth = $dbh->prepare($strSQL);

			$item_data = $dbo->getDeleteItem();
			$key_data = $dbo->getDeleteKey();

			$ret = $this->bindSQL($sth, $dbo, $item_data, $key_data);
			debug_log("bindSQL: ret($ret)") ;
			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_DELETE;
		}

		return $ret;
	}

	// 削除処理(物理)
	public function HardDelete($dbh, $dbo) {
		$ret = 0;
		try {
			$strSQL = $this->getHardDelSQL();
			$sth = $dbh->prepare($strSQL);

			$item_data = $dbo->getHardDeleteItem();
			$key_data = $dbo->getHardDeleteKey();

			$ret = $this->bindSQL($sth, $dbo, $item_data, $key_data);
			debug_log("bindSQL: ret($ret)") ;
			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_HARD_DELETE;
		}

		return $ret;
	}

	// 取得処理
	public function Get($dbh, $dbdata, &$return) {
		$ret = 0;
		try {
			$dbo = $dbdata->getDBO();

			$strSQL = $this->getGetSQL();
			$sth = $dbh->prepare($strSQL);

			$item_data = array();
			$key_data = $dbo->getGetKey();

			$ret = $this->bindSQL($sth, $dbo, $item_data, $key_data);
			debug_log("bindSQL: ret($ret)") ;
			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
			}
			if ($ret <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			$all = $sth->fetchAll();
			if (count($all) <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			if (count($all) > 1) {
				$ret = ERR_DB_TOO_MUCH;
				return $ret;
			}

			foreach($all as $loop){
				$return = $loop;
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_GET;
		}

		return $ret;
	}

	// 一覧取得処理
	public function Select($dbh, $a_where, $a_order, $a_other, &$return, $cb=null,$prm=null,&$ptr=null) {
		$ret = 0;
		try {
			$strSQL = $this->getSelectSQL();

			if (!empty($a_where)) {
				$strSQL .= " where ".$a_where;
			}
			if (!empty($a_order)) {
				$strSQL .= " order by ".$a_order;
			}
			if (!empty($a_other)) {
				$strSQL .= " ".$a_other;
			}

			$sth = $dbh->prepare($strSQL);

			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
			}
			if ($ret <= 0) {
				return ERR_DB_SELECT;
			}
			if ( $cb != null ) {
				while( $row = $sth->fetch() ) {
					if ( $cb($dbh,$row,$prm,$ptr) != true ) {
						break;
					}
				}
			} else {
				if($row = $sth->fetchAll()) {
					$return = $row;
				} else {
					$ret = ERR_DB_NOT_FOUND;
					return $ret;
				}
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_SELECT;
		}

		return $ret;
	}

	public function GetID($dbh, &$recid) {
		$ret = 0;
		try {
			$strSQL = $this->getGetIDSQL();
			$sth = $dbh->prepare($strSQL);

			$ret = $sth->execute();
			debug_log("execute: ret(" . ($ret==true?"true":"false") . ")") ;
			if ( $ret == false ) {
				debug_log($strSQL);
				debug_log(print_r($sth->errorInfo(),True));
			}
			if ($ret <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			$all = $sth->fetchAll();
			if (count($all) <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			if (count($all) > 1) {
				$ret = ERR_DB_TOO_MUCH;
				return $ret;
			}

			foreach($all as $loop){
				$recid = $loop["nextval"];
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_GET;
		}

		return $ret;
	}

	public function getSysId($dbh, &$recid) {
		$ret = 0;
		try {
			$strSQL = "select 2000000000000000 + (to_number(to_char(now(),'FMYYMMDD'),'999999') * 10000000000) + nextval('seq_sysid') nextval";
			$sth = $dbh->prepare($strSQL);

			$ret = $sth->execute();
			if ($ret <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			$all = $sth->fetchAll();
			if (count($all) <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			if (count($all) > 1) {
				$ret = ERR_DB_TOO_MUCH;
				return $ret;
			}

			foreach($all as $loop){
				$recid = $loop["nextval"];
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_GET;
		}

		return $ret;
	}

	public function getOpeId($dbh, &$recid) {
		$ret = 0;
		try {
			$strSQL = "select nextval('seq_opeid') nextval";
			$sth = $dbh->prepare($strSQL);

			$ret = $sth->execute();
			if ($ret <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			$all = $sth->fetchAll();
			if (count($all) <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			if (count($all) > 1) {
				$ret = ERR_DB_TOO_MUCH;
				return $ret;
			}

			foreach($all as $loop){
				$recid = $loop["nextval"];
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_GET;
		}

		return $ret;
	}

	public function getWebSes($dbh, &$recid) {
		$ret = 0;
		try {
			$strSQL = "select (to_number(to_char(now(),'FMYYMMDD'),'999999') * 10000000000) + nextval('ses_t_web_session_manager') nextval";
			$sth = $dbh->prepare($strSQL);

			$ret = $sth->execute();
			if ($ret <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			$all = $sth->fetchAll();
			if (count($all) <= 0) {
				$ret = ERR_DB_NOT_FOUND;
				return $ret;
			}
			if (count($all) > 1) {
				$ret = ERR_DB_TOO_MUCH;
				return $ret;
			}

			foreach($all as $loop){
				$recid = $loop["nextval"];
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			$ret = ERR_DB_GET;
		}

		return $ret;
	}

	// バインド処理
	public function bindSQL($sth, $a_object, $item_data = null, $key_data = null) {
		try {
			$i = 1;
			if (!is_null($item_data) && !is_null($key_data)) {
				foreach ($a_object as $key => $value) {
					$key_preg = preg_replace('/m_/', '', $key);
					if (array_search($key_preg, $item_data) !== false) {
						debug_log("($i) item  $key: ". $value) ;
						if ($value !== '') {
							$ret = $sth->bindValue($i, $value);
							if (!$ret) {
								print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value);
							return ERR_DB_BIND;
							}
						}
						else {
							debug_log("($i) $key is null") ;
							$null = null;
							$ret = $sth->bindValue($i, $null, PDO::PARAM_NULL);
							if (!$ret) {
								print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value.' type = '.$type);
								return ERR_DB_BIND;
							}
						}
						$i++;
					}
				}
				foreach ($a_object as $key => $value) {
					$key_preg = preg_replace('/m_/', '', $key);
					if (array_search($key_preg, $key_data) !== false) {
						debug_log("($i) key $key: ". $value) ;
						if ($value !== '') {
							$ret = $sth->bindValue($i, $value);
							if (!$ret) {
								print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value);
								return ERR_DB_BIND;
							}
						}
						else {
							$null = null;
							$ret = $sth->bindValue($i, $null, PDO::PARAM_NULL);
							if (!$ret) {
								print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value.' type = '.$type);
								return ERR_DB_BIND;
							}
						}
						$i++;
					}
				}
			}
			else {
				$i = 1;
				foreach ($a_object as $key => $value) {
					$key_preg = preg_replace('/m_/', '', $key);
					debug_log("($i) $key_preg: ". $value) ;
					if (isset($value)) {
						$ret = $sth->bindValue($i, $value);
						if (!$ret) {
							print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value);
							return ERR_DB_BIND;
						}
					}
					else {
						$null = null;
						$ret = $sth->bindValue($i, $null, PDO::PARAM_NULL);
						if (!$ret) {
							print_r('bindエラー'.$ret.' i = '.$i.' value = '.$value.' type = '.$type);
							return ERR_DB_BIND;
						}
					}
					$i++;
				}
			}
		}
		catch (Exception $e) {
			print_r($e->getMessage());
			debug_log("exception: $e->getMessage()") ;
			return ERR_EXCEPTION;
		}

		return $i;
	}
}

?>
