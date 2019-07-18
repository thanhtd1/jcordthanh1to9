drop table IF EXISTS bank_caseid;
create table bank_caseid (
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
	,bankid	BIGINT	NOT NULL	-- バンクID
	,seq_no	BIGINT	NOT NULL	-- バンク毎年毎症例ID

	, constraint bank_caseid_pkey primary key ( recid )
	
	, constraint bank_caseid_unique1 UNIQUE(sysid)
, constraint bank_caseid_unique2 UNIQUE(bankid)
);
