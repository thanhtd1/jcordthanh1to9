drop table IF EXISTS trump_master;
create table trump_master (
	sysid	BIGINT	NOT NULL	-- システムID
	,opeid	BIGINT	NOT NULL	-- 操作ID
	,sys_mode	INTEGER	NOT NULL	-- 操作種別
	,recid	BIGSERIAL	NOT NULL	-- RECID
	,sys_date	TIMESTAMP	NOT NULL	-- 処理日
	,sys_user_id	VARCHAR(20)	NOT NULL	-- 処理ユーザID
	,reg_date	TIMESTAMP	NOT NULL	-- 作成日
	,reg_user_id	VARCHAR(20)	NOT NULL	-- 作成ユーザID
	,upd_date	TIMESTAMP	NOT NULL	-- 更新日
	,upd_user_id	VARCHAR(20)	NOT NULL	-- 更新ユーザID
	,del_flg	INTEGER	NOT NULL   DEFAULT 0	-- 削除フラグ
	,curr_flag	INTEGER	NOT NULL	-- カレントフラグ
	,err_flag	INTEGER	NOT NULL	-- エラーフラグ
	,bankid1	INTEGER	NOT NULL	-- バンクID1
	,bank_no1	VARCHAR(16)	NOT NULL	-- 管理番号1
	,caseid1	VARCHAR(16)	-- 症例番号1
	,bankid2	INTEGER	-- バンクID2
	,bank_no2	VARCHAR(16)	-- 管理番号2
	,caseid2	VARCHAR(16)	-- 症例番号2
	,fname	VARCHAR(128)	-- ファイル名
	,upload_date	TIMESTAMP	-- アップロード日時
	,download_date	TIMESTAMP	-- ダウンロード日時
	,trans_date	TIMESTAMP	-- 移植日
	,hosp_code	VARCHAR(20)	-- 施設コード
	,bankid_ok1	INTEGER	-- 正バンクID1
	,bank_no_ok1	VARCHAR(16)	-- 正管理番号1
	,bankid_ok2	INTEGER	-- 正バンクID2
	,bank_no_ok2	VARCHAR(16)	-- 正管理番号2
	,ok_flag	INTEGER	-- 解決フラグ

	, constraint trump_master_pkey primary key ( recid )
	
	, constraint trump_master_unique1 UNIQUE(sysid)
, constraint trump_master_unique2 UNIQUE(curr_flag,err_flag,bankid1,bank_no1)
);
