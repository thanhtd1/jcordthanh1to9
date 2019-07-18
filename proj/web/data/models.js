/*
 * "data/models.js"
 */
angular.module("nispApp").service("CommonModels", ["groundwork", function(gw) {
    "use strict";

    var l_this = this;
    var l_scope;
    this.setScope = function(a_scope) {
    	l_scope = a_scope;
    	a_scope.constants = l_this.constants;
    }
    this.constants = {
    	find: function(a_name,a_key) {
    		var l_list = l_scope.constants[a_name];
    		for (var l_idx in l_list) {
    			if (l_list[l_idx].k == a_key) return l_list[l_idx].n;
    		}
    		return "";
    	},
    	getPrefCd: function(startKey, startVal) {
    		var prefCd = [];
    		for (var i in this.prefCdOrg) {
    			var key = (i==0) ? startKey : i;
    			var val = (i==0) ? startVal : this.prefCdOrg[i];
    			prefCd[i] = {k: key, n: val};
    		}
    		return prefCd;
    	},
    	getPrefCd2: function(startKey, startVal) {
    		var prefCd = this.getPrefCd(startKey, startVal);
    		prefCd.unshift({k:'',n:'--'});
    		return prefCd;
    	},
    	getPrefCd_unselected: function(startKey, startVal) {
    		var prefCd = this.getPrefCd(startKey, startVal);
    		prefCd.unshift({k:'',n:'--'});
    		return prefCd;
    	},
    };

    this.string = {
        label: "文字列"
    };

    this.integer = {
        label: "整数",
        normalizations: "hankaku",
        validations: {
            integer: ['integer'],
	    }
    };

    this.int = {
        label: "整数",
        base_model: "integer",
    };

    this.boolean = {
        label: "論理値",
        validations: {
        	boolean: ['boolean'],
	    }
    };

    this.primary_key = {
    	base_model: "integer",
    	primary_key: true
    };

    this.date = {
    	label: "西暦",
    	sub_items : {
    		year: {
    			base_model: "string",
                normalizations: "hankaku",
    		},
    		month: {
    			base_model: "string",
                normalizations: "zero2_fill",
    		},
    		day: {
    			base_model: "string",
                normalizations: "zero2_fill",
    		},
    	},
	    normalizations: "to_ymd",
	    visualizations: "from_ymd",
    };
    this.japanese_date = {
        label: "和暦",
        sub_items : {
            nengo: {
            	base_model: "string",
                normalizations: "hankaku",
            },
            year: {
            	base_model: "string",
/*
                validations: {
	    	        "min_value1": ["min_value",1],
	    	        "max_value99": ["max_value",99],
                }
*/
            },
            month: {
            	base_model: "string",
                normalizations: "zero2_fill",
/*
                validations: {
	    	        "min_value1": ["min_value",1],
	    	        "max_value12": ["max_value",12],
                }
*/
             },
            day: {
            	base_model: "string",
                normalizations: "zero2_fill",
/*
                validations: {
	    	        "min_value1": ["min_value",1],
	    	        "max_value31": ["max_value",31],
                }
*/
            },
        },
        normalizations: "japanese_ymd",
        visualizations: "ymd_japanese",
    };
    this.constants.nengos = gw.nengoNames;
    this.constants.years = ['--','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64',];
    this.constants.months = ['--','01','02','03','04','05','06','07','08','09','10','11','12'];
    this.constants.days = ['--','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31',];

    this.japanese_timestamp = {
    	label: "和暦日時",
    	base_model: "japanese_date",
    	sub_items: {
    		hour: {
    			base_model: "string",
    			normalizations: "zero2_fill",
    		},
			min: {
				base_model: "string",
				normalizations: "zero2_fill",
			},
			sec: {
				base_model: "string",
				normalizations: "zero2_fill",
			},
			msec: {
				base_model: "string",
			}
    	},
    	normalizations: "japanese_timestamp",
    	visualizations: "timestamp_japanese",
    };
    this.timestamp = {
    	label: "西暦日時",
    	base_model: "date",
    	sub_items: {
    		hour: {
    			base_model: "string",
    			normalizations: "zero2_fill",
    		},
			min: {
				base_model: "string",
				normalizations: "zero2_fill",
			},
			sec: {
				base_model: "string",
				normalizations: "zero2_fill",
			},
			msec: {
				base_model: "string",
			}
    	},
    	normalizations: "to_ymdhms",
    	visualizations: "from_ymdhms",
    };

    this.recid = {
		label: "レコードＩＤ",
       	base_model: "string",
       	primary_key: true,
    };

    this.passwd = {
    	label: "パスワード",
    	base_model: "string",
    	validations: {
    		"min_length4": ["min_length",4],
        	"max_length20": ["max_length",20],
        	"han_alnumsym": ["alnumsym"],
    	},
    };

    this.seiKanji = {
        label: "氏名　姓",
        base_model: "string",
        "validations": {
            "max_length17": ["max_length",17]
        }
    };
    this.meiKanji = {
        label: "氏名　名",
        base_model: "string",
        "validations": {
            "max_length17": ["max_length",17]
        }
    };

    this.seiKana = {
        label: "ふりがな姓",
        base_model: "string",
        "validations": {
            "max_length20": ["max_length",20]
        }
    };
    this.meiKana = {
        label: "ふりがな名",
        base_model: "string",
        "validations": {
            "max_length20": ["max_length",20]
        }
    };

    this.seiKanaNayose = {
        // {#496} 基本のデータ項目定義の拡充
        label: "ふりがな姓(名寄せ用)",
        base_model: "string",
        "validations": {
            "max_length20": ["max_length",20]
        }
    };
    this.meiKanaNayose = {
        // {#496} 基本のデータ項目定義の拡充
        label: "ふりがな名(名寄せ用)",
        base_model: "string",
        validations: {
            "max_length20": ["max_length",20]
        }
    };

    this.birthday = {
        // {#496} 基本のデータ項目定義の拡充
        label: "誕生日",
       	base_model: "japanese_date"
    };

    this.sex = {
        // {#496} 基本のデータ項目定義の拡充
        label: "性別",
        base_model: "integer",
    };
    this.constants.sex = [{k:'--',n:0},{k:'男',n:1},{k:'女',n:2},];

    this.postCd = {
        // {#496} 基本のデータ項目定義の拡充
		label: "郵便番号",
		sub_items: {
			code1: {
				base_model: "string",
		        validations: {
		        	"integer": ["integer"],
		            "max_length3": ["max_length",3],
		            "min_length3": ["min_length",3]
		        }
			},
			code2: {
				base_model: "string",
		        validations: {
		        	"integer": ["integer"],
		            "max_length4": ["max_length",4],
		            "min_length4": ["min_length",4]
		        }
			}
		}
    };
/*
    this.prefCd = {
        // {#496} 基本のデータ項目定義の拡充
		label: "都道府県コード",
        base_model: "integer",
        validations: {
        	"integer": ["integer"],
            "prefecture": ["max_value",48],
        }
    };
    this.constants.prefCd = ["--","北海道","青森","岩手","宮城","秋田","山形","福島","茨城","栃木","群馬","埼玉","千葉","東京","神奈川","新潟","富山","石川","福井","山梨","長野","岐阜","静岡","愛知","三重","滋賀","京都","大阪","兵庫","奈良","和歌山","鳥取","島根","岡山","広島","山口","徳島","香川","愛媛","高知","福岡","佐賀","長崎","熊本","大分","宮崎","鹿児島","沖縄","海外",],
*/
    this.constants.prefCdOrg = ["","北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県","茨城県","栃木県","群馬県","埼玉県","千葉県","東京都","神奈川県","新潟県","富山県","石川県","福井県","山梨県","長野県","岐阜県","静岡県","愛知県","三重県","滋賀県","京都府","大阪府","兵庫県","奈良県","和歌山県","鳥取県","島根県","岡山県","広島県","山口県","徳島県","香川県","愛媛県","高知県","福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県","沖縄県","海外",],
    

    this.email = {
        label: "メール",
        base_model: "string",
        normalizations: "hankaku",
        validations: {
            email_format: ["mail"]
        }
    };

    this.bloodAbo = {
		label: "ＡＢＯ血液型",
        base_model: "integer",
        // {#496} 基本のデータ項目定義の拡充
    };
    this.constants.bloodAbo = [ {n:'A',k:1},{n:'B',k:2},{n:'O',k:3},{n:'AB',k:4} ];

    this.bloodRh = {
        // {#496} 基本のデータ項目定義の拡充
		label: "Ｒｈ血液型",
        base_model: "integer",
    };
    this.constants.bloodRh = [{n:'Rh+',k:1},{n:'Rh-',k:2}];

    this.height = {
        // {#496} 基本のデータ項目定義の拡充
		label: "身長",
        base_model: "integer",
    };

    this.weight = {
        // {#496} 基本のデータ項目定義の拡充
		label: "体重",
        base_model: "integer",
    };
    // {#496} 基本のデータ項目定義の拡充
}]);
