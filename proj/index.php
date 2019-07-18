<?php
    require_once(".config.php");
    require_once(COMM_DIR . "util_date.php");
	$RECID = 0;		// レコードID
	$DEL_FLAG = 0;	// 削除フラグ
	$sys_date = getCurrentDateTime(DATE_TIME_KIND2);	// 登録日
    $UPD_DATE = NULL;	// 更新日
    $Name = "tran duy thanh";
    //$MAIL = "thanhtd";
    $Company_Name = "SPRITE PLUS";
    $Division = "NIS PLUS";
    $sysid =0;
    $user_id = 0;
    $user_name ='thanhtd@vn.sprite.jp';
    $pass = '123456789';
    $reg_stat = 1;
    $opeid = 0;
    // Insert cord_user
    // for($i = 1; $i <= 10; $i++) {
    //     $sysid++;
    //     $RECID++;
    //     $user_id++;
    //     $user_name ='thanhtd';
    //     $Name = "tran duy thanh" . $i;
    //     $MAIL = "thanhtd". $i ."@vn.sprite.jp";
    //     $Company_Name =  "SPRITE PLUS" . $i;
    //     $org_name = 'tranduythanh'. $i;
    //     $empname = 'thanhtd'. $i;
    //     $person = 'Kinh';
    //     $opeid++;
    //     echo "INSERT INTO public.cord_user(
    //         sysid,
    //         opeid,
    //         sys_mode,
    //         recid,
    //         sys_date,
    //         sys_user_id,
    //         reg_date,
    //         reg_user_id, 
    //         upd_date,
    //         upd_user_id, 
    //         del_flg,
    //         user_name,
    //         bankid,
    //         passwd,
    //         org_name,
    //         empname,
    //         id_info)
    //         VALUES (
    //             '$sysid',
    //             $opeid,
    //             1,
    //             $RECID,
    //             '$sys_date',
    //             1,
    //             '$sys_date',
    //             1,
    //             '$sys_date',
    //             1,
    //             0,
    //             '$user_name',
    //             1,
    //             '$pass',
    //             '$org_name',
    //             '$empname',
    //             'id');";
    //     echo "<br/>";
    // }