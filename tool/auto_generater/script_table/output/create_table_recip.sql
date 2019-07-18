drop table IF EXISTS recip;
create table recip (
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
	,birthday	TIMESTAMP	NOT NULL	-- 生年月日
	,hla_a1	INTEGER	NOT NULL	-- HLA-A(1)
	,hla_a2	INTEGER	NOT NULL	-- HLA-A(2)
	,hla_b1	INTEGER	NOT NULL	-- HLA-B(1)
	,hla_b2	INTEGER	NOT NULL	-- HLA-B(2)
	,hla_dr1	INTEGER	NOT NULL	-- HLA-DR(1)
	,hla_dr2	INTEGER	NOT NULL	-- HLA-DR(2)
	,blood_abo	VARCHAR(2)	NOT NULL	-- ABO血液型
	,blood_rh	VARCHAR(1)	NOT NULL	-- Rh血液型
	,sex	INTEGER	NOT NULL	-- 性別
	,cancel_date	TIMESTAMP	-- 最新取消日
	,reserve_date	TIMESTAMP	-- 最古申込日
	,note	VARCHAR(20)	-- 備考
	,accept	INTEGER	-- 受理フラグ
	,weight	INTEGER	-- 体重
	,match_num	INTEGER	-- 適合抗原数
	,result_sort	INTEGER	-- 検索結果整列順
	,result_num	INTEGER	-- 検索結果表示数
	,userid	INTEGER	-- 施設ユーザID
	,hla_cw1	INTEGER	-- HLA-Cw(1)
	,hla_cw2	INTEGER	-- HLA-Cw(2)
	,hla_dq1	INTEGER	-- HLA-DQ(1)
	,hla_dq2	INTEGER	-- HLA-DQ(2)
	,a_1	INTEGER	-- A(1)
	,a_2	INTEGER	-- A(2)
	,b_1	INTEGER	-- B(1)
	,b_2	INTEGER	-- B(2)
	,c_1	INTEGER	-- C(1)
	,c_2	INTEGER	-- C(2)
	,drb1_1	INTEGER	-- DRB1(1)
	,drb1_2	INTEGER	-- DRB1(2)
	,dqb1_1	INTEGER	-- DQB1(1)
	,dqb1_2	INTEGER	-- DQB1(2)

	, constraint recip_pkey primary key ( recid )
	
	, constraint recip_unique1 UNIQUE(sysid)
, constraint recip_unique2 UNIQUE(birthday,hla_a1,hla_a2,hla_b1,hla_b2,hla_dr1,hla_dr2,blood_abo,blood_rh,sex)
);
