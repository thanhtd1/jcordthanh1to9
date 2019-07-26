<?php
	date_default_timezone_set('Asia/Tokyo');
	const APP_LANG = "UTF-8";

	// DB 関連の定義
	// DB 関連の定義
	const DB_SERVER = "192.168.201.74";
	const DB_NAME = "jcorddb";
	const DB_USER = "postgres";
	const DB_PASS = "123456789";
	const DB_PORT = "5432";

	// APIの戻り値
	const API_RET_OK = 1;
	const API_RET_NG = 0;

	// 戻り値関係
	const RET_OK = 0;

	//エラー関連
	const ERR_EXCEPTION = -99;
	const ERR_VALIDATE = -1;

	// エラー(DB関連)
	const ERR_DB_CONNECT = -100;
	const ERR_DB_INSERT = -101;
	const ERR_DB_UPDATE = -102;
	const ERR_DB_DELETE = -103;
	const ERR_DB_HARD_DELETE = -104;
	const ERR_DB_GET = -105;
	const ERR_DB_SELECT = -106;
	const ERR_DB_BIND = -107;
	const ERR_DB_NOT_FOUND = -110;
	const ERR_DB_TOO_MUCH = -111;
	const ERR_DB_DUPLICATE = -112;

	// トランザクション関係
	const TRANS_ON = 1;	// トランザクションを利用する。
	const TRANS_OFF = 0;	// トランザクションを利用しない。

	// DBクローズ関連
	const DB_OK = 0;
	const DB_NG = -1;

	// SYS_MODE(DB関連)
	const SYS_MODE_INSERT = 1;
	const SYS_MODE_UPDATE = 2;
	const SYS_MODE_DELETE = 3;

	define("SESSION_TIMEOUT", ((60*24)*7) );

	// 
	const FLAG_INSERT = 0;
	const FLAG_UPDATE = 1;
	const FLAG_DELETE = 2;
	const FLAG_GET = 3;

?>
