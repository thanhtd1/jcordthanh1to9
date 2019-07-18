drop table IF EXISTS cord_user;
create table cord_user (
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
	,user_name	VARCHAR(12)	NOT NULL	-- ユーザ名
	,passwd	VARCHAR(16)	NOT NULL	-- パスワード
	,org_name	VARCHAR(80)	-- 機関名称
	,empname	VARCHAR(60)	-- 所属
	,person	VARCHAR(36)	-- 担当者
	,furigana	VARCHAR(42)	-- ふりがな
	,tel_num1	VARCHAR(16)	-- 電話番号
	,tel_num2	VARCHAR(10)	-- 内線番号
	,fax_num	VARCHAR(16)	-- FAX番号
	,zip_code	VARCHAR(8)	-- 郵便番号
	,address1	VARCHAR(80)	-- 住所
	,address2	VARCHAR(60)	-- ビル名等
	,e_mail1	VARCHAR(128)	-- E-Mailアドレス
	,e_mail2	VARCHAR(128)	-- Mailアドレス2
	,e_mail3	VARCHAR(128)	-- Mailアドレス3
	,e_mail4	VARCHAR(128)	-- Mailアドレス4
	,e_mail5	VARCHAR(128)	-- Mailアドレス5
	,kind	INTEGER	NOT NULL	-- 種別
	,note	VARCHAR(80)	-- 備考
	,id_info	VARCHAR(16)	NOT NULL	-- ユーザ情報
	,lock_flag	INTEGER   DEFAULT 0	-- ロックフラグ
	,lock_time	TIMESTAMP   DEFAULT null	-- ロック日時
	,lock_cnt	INTEGER   DEFAULT 0	-- ロック回数
	,pass_upd_date	TIMESTAMP	-- パスワード更新日

	, constraint cord_user_pkey primary key ( recid )
	
	, constraint cord_user_unique1 UNIQUE(sysid)
, constraint cord_user_unique2 UNIQUE(user_name)
);
