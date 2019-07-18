drop table IF EXISTS trump_item;
create table trump_item (
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
	,trumpid	BIGINT	NOT NULL	-- TRUMP-ID
	,nth	INTEGER	NOT NULL	-- 項目番号
	,key	VARCHAR(256)	NOT NULL	-- 項目名
	,value	VARCHAR(1024)	-- 項目値

	, constraint trump_item_pkey primary key ( recid )
	
	, constraint trump_item_unique1 UNIQUE(sysid)
, constraint trump_item_unique2 UNIQUE(trumpid,nth)
);
