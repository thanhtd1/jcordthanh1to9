<?php
require_once './.config.php';
require_once './vendor/autoload.php';
require_once(COMM_DIR . "util_date.php");
// require_once(COMM_DIR . "define.php");
// require_once(COMM_DIR . "logger.php");
// require_once(APD_DIR . "apdPerson.php");
// require_once(DBSVC_DIR . "dbsvcCommon.php");
// require_once(DBSVC_DIR . "dbsvcPerson.php");
require_once './tests/getData.php';

use PHPUnit\Framework\TestCase;
use Colors\Display;
class newTest extends TestCase
{
    public function testInsert(){
        $data = new getData;
        $func = substr(__FUNCTION__, 4);
        $data = $data->readData($func);

        $dbh = new dbsvcCommon();
        $dbh->connect();
        $l_dbh = $dbh->getConnection();
        $rowIndex = 1;

        foreach($data as $func_name => $func_value) {
            foreach($func_value as $key => $value) {
                $rowIndex++;
                $a_in_apd = new apdUser();//class name
                $a_in_apd->convertData($value);      
        
                $l_svcUser = new dbsvcCord_user($l_dbh);

                $l_user_id = 0;
                //SYSIDを取得する。
                $l_sysid = 0;
                //$l_rtn = $l_svcUser->getSysId($l_dbh, $l_sysid);
                
                //OPEIDを取得する。
                // $l_opeid = 0;
                // $l_rtn = $l_svcUser->getOpeId($l_dbh, $l_opeid);
                
                // 日付を取得する。
                $l_date = getCurrentDateTime(DATE_TIME_KIND2);
                // a_sess
                $a_sess = array();
                $a_sess['USER_ID'] = 1;

                // Userデータの登録
                $l_dbdCord_user = $a_in_apd->getDBDUser();
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYSID, $l_sysid);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_OPEID, $l_opeid);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_MODE, SYS_MODE_INSERT);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_DATE, $l_date);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_SYS_USER_ID, $a_sess['USER_ID']);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_REG_DATE, $l_date);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_REG_USER_ID, $a_sess['USER_ID']);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_DATE, $l_date);
                //$l_dbdCord_user->setData($l_dbdCord_user::DBD_UPD_USER_ID, $a_sess['USER_ID']);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_PASS_UPD_DATE, $l_date);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_FLAG, 0);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_TIME, null);
                $l_dbdCord_user->setData($l_dbdCord_user::DBD_LOCK_CNT, 0);

                // params
                $array_params = array();
                $array_params["l_dbh"] = $l_dbh;
                $array_params["l_dbdCord_user"] = $l_dbdCord_user;
                $array_params["&l_user_id"] = &$l_user_id;
        
                try {
                    $result = call_user_func_array([$l_svcUser,$func_name], $array_params);
                    //$result = $l_svcUser->Insert($l_dbh, $l_dbdCord_user, $l_user_id);
                    $this->assertTrue($result);
                    echo Display::OK("\nInsert user data successfully". " at line ". $rowIndex);
                } catch(Exception $e) {
                    echo $e->getMessage(); 
                    echo Display::error("\nInsert user data fail". " at line ". $rowIndex);           
                }
              
                //$this->assertTrue($result==-110);
            }
        }
    }
}
