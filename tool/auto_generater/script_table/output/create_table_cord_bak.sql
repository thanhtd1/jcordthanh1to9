drop table IF EXISTS cord_bak;
create table cord_bak (
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
	,bankid	BIGINT	-- バンクコード
	,bank_no	VARCHAR(16)	-- バンク内管理番号
	,receipt_bankid	BIGINT	-- 調整バンクコード
	,receipt_bank_no	VARCHAR(16)	-- 調整バンク内管理番号
	,reg_stat	INTEGER	-- 登録状態
	,hla_a1	INTEGER	-- HLA-A(1)
	,hla_a2	INTEGER	-- HLA-A(2)
	,hla_b1	INTEGER	-- HLA-B(1)
	,hla_b2	INTEGER	-- HLA-B(2)
	,hla_cw1	INTEGER	-- HLA-Cw(1)
	,hla_cw2	INTEGER	-- HLA-Cw(2)
	,hla_dr1	INTEGER	-- HLA-DR(1)
	,hla_dr2	INTEGER	-- HLA-DR(2)
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
	,collect_date	TIMESTAMP	-- 採取年月日
	,blood_abo	VARCHAR(2)	-- ABO血液型
	,blood_rh	VARCHAR(1)	-- Rh血液型
	,sex	INTEGER	-- 性別
	,separate_method	INTEGER	-- 分離方法
	,freezing_method	INTEGER	-- 凍結方法
	,protect_liq	VARCHAR(40)	-- 凍害保護液
	,preserve_vol	INTEGER	-- 保存液量
	,preserve_temp	INTEGER	-- 保存温度
	,cell_num	INTEGER	-- 有効細胞数
	,cd34_num	INTEGER	-- CD34細胞数
	,cd34_method	VARCHAR(40)	-- CD34測定方法
	,cfu_num	INTEGER	-- CFU総数
	,cfugm_num	INTEGER	-- CFU-GM数
	,cfugm_method	VARCHAR(40)	-- CFU測定方法
	,cmvigm_method	INTEGER	-- CMV-IgM検査結果
	,cmvdna_method	INTEGER	-- CMV-DNA検査結果
	,note	VARCHAR(200)	-- 備考
	,supply_date	TIMESTAMP	-- 供給年月日
	,supply_hosp	VARCHAR(200)	-- 供給病院
	,trans_date	TIMESTAMP	-- 移植年月日
	,trans_flag	INTEGER	-- 移植実施報告
	,hosp_code	VARCHAR(20)	-- 施設コード
	,rbc_rate	INTEGER	-- 赤血球率
	,caseid	VARCHAR(16)	-- 症例番号
	,trump_hosp_code	VARCHAR(20)	-- TRUMP施設コード
	,harvest_cell_num	INTEGER	-- 採取時有効細胞数
	,harvest_preserve_vol	INTEGER	-- 採取時液量

	, constraint cord_bak_pkey primary key ( recid )
	
	, constraint cord_bak_unique1 UNIQUE(sysid)
);
