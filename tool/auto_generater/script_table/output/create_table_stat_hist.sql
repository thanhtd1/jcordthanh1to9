drop table IF EXISTS stat_hist;
create table stat_hist (
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
	,stat_date	TIMESTAMP	NOT NULL	-- 集計年月日
	,bankid	BIGINT	NOT NULL	-- バンクコード
	,bank_name	VARCHAR(10)	-- バンク名
	,num00	INTEGER	-- 公開本数
	,num01	INTEGER	-- 申込本数
	,num03	INTEGER	-- 申込確定本数
	,num04	INTEGER	-- オンライン申込本数
	,num08	INTEGER	-- 公開取消本数
	,num09	INTEGER	-- 供給本数
	,num10	INTEGER	-- 移植実施報告本数
	,row_nth	INTEGER	-- バンク並び順

	, constraint stat_hist_pkey primary key ( recid )
	
	, constraint stat_hist_unique1 UNIQUE(sysid)
, constraint stat_hist_unique2 UNIQUE(stat_date,bankid)
);
