<?php
//======================================================
//
// 機能名：     Define constants for program.
//
// 機能ID：     .confi.php
// 継承  ：     none
// 概要  ：     Define constants used in program.
// 
// $Id:$
// $Author:$
// $Revision:$
//
//======================================================

define("API_CGI_URL",	"/lib/api/member/");
define("API_CGI_API",	"api");
define("API_CGI_APD",	"apd");
define("API_CGI_EXT",	".php");

define("TOP_DIR",	__DIR__.'/');
define("LIB_DIR",	TOP_DIR . "lib/");
define("COMM_DIR",	LIB_DIR . "common/");
define("VIEW_DIR",	LIB_DIR . "view/");
define("DATA_DIR",	LIB_DIR . "data/");
define("APD_DIR",	DATA_DIR . "apdata/");
define("DBD_DIR",	DATA_DIR . "dbdata/");
define("VALID_DIR",	LIB_DIR . "validate/");
define("VALID_L1_DIR",	VALID_DIR . "level1/");
define("VALID_L2_DIR",	VALID_DIR . "level2/");
define("VALID_L3_DIR",	VALID_DIR . "level3/");
define("VALID_L4_DIR",	VALID_DIR . "level4/");
define("VALID_L4_OPT_DIR",	VALID_L4_DIR . "l4_opt/");
define("TMP_DIR",	LIB_DIR . "templates/");
define("CMP_DIR",	TOP_DIR . "tmp/");
define("DB_DIR",        LIB_DIR . "db/");
define("DBO_DIR",       DB_DIR . "dbo/");
define("DBSVC_DIR",     DB_DIR . "dbsvc/");
define("LOGIC_DIR",     LIB_DIR . "logic/");
define("IF_DIR",     LIB_DIR . "if/");
define("WEBAPI_DIR",     LIB_DIR . "webapi/");

define("DEBUG_LOG_IS", 1);	// 1:on,0:off
define("LOGS_DIR",	TOP_DIR."log/");
define("UPDATE_LOG",    LOGS_DIR . "update_");
define("SESSION_LOG",   LOGS_DIR . "session_");
define("ACCESS_LOG",    LOGS_DIR . "access_");
define("DEBUG_LOG",	LOGS_DIR . "debug_");
define("LOG_EXT",	".log");

?>
