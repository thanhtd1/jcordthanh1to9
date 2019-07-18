drop table IF EXISTS recip_ext;
create table recip_ext (
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
	,userid	BIGINT	-- 施設ユーザID
	,cancel	INTEGER	-- 取消フラグ
	,inisyaru	VARCHAR(10)	-- イニシャル
	,sex	INTEGER	-- 性別
	,height	INTEGER	-- 身長
	,weight	INTEGER	-- 体重
	,birthday	TIMESTAMP	-- 生年月日
	,age_year	INTEGER	-- 年齢歳
	,age_month	INTEGER	-- 年齢月
	,blood_abo	VARCHAR(2)	-- ABO血液型
	,blood_rh	VARCHAR(1)	-- Rh血液型
	,sikkan_mei	VARCHAR(100)	-- 疾患名
	,byoki	VARCHAR(100)	-- 病期
	,hassyo_year	INTEGER	-- 発症時期年
	,hassyo_month	INTEGER	-- 発症時期月
	,kazoku_reki	VARCHAR(100)	-- 家族歴
	,kio_reki	VARCHAR(100)	-- 既往歴
	,gappei_syo	VARCHAR(100)	-- 合併症
	,tiryo_keika	VARCHAR(1024)	-- 治療経過
	,ketuensya_dona	INTEGER	-- 血縁者ドナー
	,kotuzui_dona	INTEGER	-- 骨髄バンクドナー
	,kotuzui_dona_num	INTEGER	-- 骨髄バンクドナー人数
	,trans_date	TIMESTAMP	-- 移植予定日
	,teikyo_date	TIMESTAMP	-- 提供予定日
	,isyoku_isi	VARCHAR(36)	-- 移植担当医師
	,sisetu_mei	VARCHAR(100)	-- 施設名
	,sinryoka_mei	VARCHAR(100)	-- 診療科名
	,zip_code	VARCHAR(8)	-- 郵便番号
	,address	VARCHAR(160)	-- 住所
	,tel_num	VARCHAR(16)	-- 電話番号
	,fax_num	VARCHAR(16)	-- FAX番号
	,e_mail	VARCHAR(128)	-- E-Mail
	,hla_a1	INTEGER	-- HLA-A(1)
	,hla_a2	INTEGER	-- HLA-A(2)
	,hla_b1	INTEGER	-- HLA-B(1)
	,hla_b2	INTEGER	-- HLA-B(2)
	,hla_cw1	INTEGER	-- HLA-Cw(1)
	,hla_cw2	INTEGER	-- HLA-Cw(2)
	,hla_dr1	INTEGER	-- HLA-DR(1)
	,hla_dr2	INTEGER	-- HLA-DR(2)
	,a_1	INTEGER	-- A(1)
	,a_2	INTEGER	-- A(2)
	,b_1	INTEGER	-- B(1)
	,b_2	INTEGER	-- B(2)
	,cw_1	INTEGER	-- C(1)
	,cw_2	INTEGER	-- C(2)
	,drb1_1	INTEGER	-- DRB1(1)
	,drb1_2	INTEGER	-- DRB1(2)
	,hosp_code	VARCHAR(20)	-- 施設コード

	, constraint recip_ext_pkey primary key ( recid )
	
	, constraint recip_ext_unique1 UNIQUE(sysid)
, constraint recip_ext_unique2 UNIQUE(reg_date,recipid,cordid)
);
