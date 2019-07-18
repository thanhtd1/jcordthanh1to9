drop table IF EXISTS bank;
create table bank (
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
	,bankid	INTEGER	-- バンクID
	,cur_bankid	INTEGER	-- 管理バンクID
	,available	INTEGER	-- 有効フラグ
	,bank_name	VARCHAR(60)	NOT NULL	-- バンク名称
	,short_name	VARCHAR(10)	-- 省略名称
	,ename	VARCHAR(80)	-- 英語名称
	,short_ename	VARCHAR(4)	NOT NULL	-- 英語省略名称
	,person	VARCHAR(36)	-- 担当者
	,tel_num	VARCHAR(16)	-- 電話番号
	,fax_num	VARCHAR(16)	-- FAX番号
	,kind	INTEGER	-- バンク種別
	,row_nth	INTEGER	-- バンク並び順

	, constraint bank_pkey primary key ( recid )
	
	, constraint bank_unique1 UNIQUE(sysid)
);
