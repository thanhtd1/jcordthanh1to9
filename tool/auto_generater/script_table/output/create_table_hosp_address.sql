drop table IF EXISTS hosp_address;
create table hosp_address (
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
	,code	VARCHAR(20)	NOT NULL	-- 施設コード
	,org_name	VARCHAR(160)	-- 施設名
	,dept_name	VARCHAR(160)	-- 診療科名
	,CONTACT_NAME	VARCHAR(160)	-- 担当者名
	,fax_num	VARCHAR(16)	-- FAX番号
	,e_mail	VARCHAR(128)	-- E-Mail

	, constraint hosp_address_pkey primary key ( recid )
	
	, constraint hosp_address_unique1 UNIQUE(sysid)
, constraint hosp_address_unique2 UNIQUE(code)
);
