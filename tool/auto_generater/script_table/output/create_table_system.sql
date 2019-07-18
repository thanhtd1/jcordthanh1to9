drop table IF EXISTS system;
create table system (
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
	,available	INTEGER	-- 有効フラグ
	,item_name	VARCHAR(32)	NOT NULL	-- 項目名
	,item_value	VARCHAR(32)	-- 項目値
	,item_note	VARCHAR(80)	-- 備考

	, constraint system_pkey primary key ( recid )
	
	, constraint system_unique1 UNIQUE(sysid)
, constraint system_unique2 UNIQUE(item_name)
);
