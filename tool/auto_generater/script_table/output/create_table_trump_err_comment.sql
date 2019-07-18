drop table IF EXISTS trump_err_comment;
create table trump_err_comment (
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
	,nth	INTEGER	NOT NULL	-- 連番
	,bankid1	INTEGER	NOT NULL	-- バンク名1
	,bank_no1	VARCHAR(30)	NOT NULL	-- 管理番号1
	,caseid1	VARCHAR(30)	-- 症例番号1
	,bankid2	INTEGER	-- バンク名2
	,bank_no2	VARCHAR(30)	-- 管理番号2
	,caseid2	VARCHAR(30)	-- 症例番号2
	,bankname	VARCHAR(128)	-- 修正バンク名

	, constraint trump_err_comment_pkey primary key ( recid )
	
	, constraint trump_err_comment_unique1 UNIQUE(sysid)
, constraint trump_err_comment_unique2 UNIQUE(trumpid,nth)
);
