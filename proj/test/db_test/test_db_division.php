<?php
session_start();
require_once("../../.config.php");
require_once(COMM_DIR . "define.php");
require_once(COMM_DIR . "util_date.php");
require_once(DBO_DIR . "dboDivision.php");
require_once(DBSVC_DIR . "dbsvcDivision.php");
require_once(DBSVC_DIR . "dbsvcCommon.php");

$db_con = new dbsvcCommon();
$rtn = $db_con->connect();

$dbh = $db_con->getConnection();

$dbo = new dboDivision();
$svc = new dbsvcDivision($dbh);

$db_con->begintran();

$recid = 2;
//$svc->GetID($db_con->getConnection(), $recid);

$dbo->recid = $recid;
$dbo->ref_person_id = 7;
$dbo->division = "システム部";
$dbo->start_date = "2019/04/01";
$dbo->del_flag = 0;
$dbo->reg_date = getCurrentDateTime(DATE_TIME_KIND2);
$dbo->upd_date = getCurrentDateTime(DATE_TIME_KIND2);

print_r("insert_sql = " . $svc->getInsSQL()."\n");
print_r("update_sql = " . $svc->getUpdSQL()."\n");
print_r("delete_sql = " . $svc->getDelSQL()."\n");
print_r("hard_delete_sql = " . $svc->getHardDelSQL()."\n");
print_r("get_sql = " . $svc->getGetSQL()."\n");

$return = array();

//$ret = $svc->Insert($db_con->getConnection(), $dbo);
$ret = $svc->Update($db_con->getConnection(), $dbo);
//$ret = $svc->Delete($db_con->getConnection(), $dbo);
//$ret = $svc->HardDelete($db_con->getConnection(), $dbo);
//$ret = $svc->Get($db_con->getConnection(), $dbo, $return);
$ret = $svc->GetForPersonId($db_con->getConnection(), $dbo, $return);
if ($ret < 0) {
	$db_con->rollback();
}
else {
	$db_con->commit();
}

var_dump($return);

$db_con->disconnect();

?>
