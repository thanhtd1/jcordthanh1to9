drop table IF EXISTS state;
create table state (
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
	,recipid	BIGINT	-- 患者ID
	,cordid	BIGINT	NOT NULL	-- さい帯血ID
	,user_id	TIMESTAMP	-- 更新ユーザID
	,reserve_date	TIMESTAMP	-- 申込日
	,cancel_date	TIMESTAMP	-- 取消日
	,supply_date	TIMESTAMP	-- 供給年月日
	,supply_hosp	VARCHAR(100)	-- 供給病院
	,hosp_code	VARCHAR(20)	-- 施設コード
	,rank	VARCHAR(3)	-- 適合ランク
	,userid	BIGINT	-- 施設ユーザID
	,cocktail	INTEGER	-- カクテル移植フラグ
	,search_number	INTEGER	-- 検索数
	,fit_number	INTEGER	-- 適合数

	, constraint state_pkey primary key ( recid )
	
	, constraint state_unique1 UNIQUE(sysid)
);
