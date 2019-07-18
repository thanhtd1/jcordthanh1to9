/*
 * "groundwork.js" provides foundational utilities
 */
angular.module("nispApp").service("groundwork", function() {
    "use strict";

    var l_this = this;

    this.LogicIntegrityCollapsedException = function() {
        this.message = "該当するロジックが見つかりません。";
    };

    this.DataConsistencyBrokenException = function() {
        this.message = "データの一貫性が崩れています。";
    };

    this.exists = function(arg) {
        return !(arg === undefined || arg === null || arg === false || arg === '' || (l_this.isArray(arg) && arg.length == 0));
    };

    this.isString = function(arg) {
        return angular.isString(arg);  // TODO 第三者ライブラリーに依存せず、生JSから実装するのが望ましい
    };

    this.isNumeric = function(arg) {
        return ! isNaN(Number(arg));
    };

    this.isArray = function(arg) {
        return angular.isArray(arg);  // TODO 第三者ライブラリーに依存せず、生JSから実装するのが望ましい
    };

    this.isObject = function(arg) {
        return angular.isObject(arg);  // TODO 第三者ライブラリーに依存せず、生JSから実装するのが望ましい
    };

    this.isFunction = function(arg) {
    	return angular.isFunction(arg);
    }

    this.isService = function() {
        var param = l_this.exists(location.search) ? location.search.match(/c=(.*?)(&|$)/)[1] : "";
        if(param) {
            var l_element = document.getElementById("main");
            var l_content = angular.element(l_element).scope().$parent.app.view;
            var urlParam = new RegExp(param);
            return urlParam.test(l_content);
        } else {
            return false;
        }
    }

    this.count = function(arg) {
        if (l_this.isObject(arg)) {
            return Object.keys(arg).length;
        } else if (l_this.isArray(arg)) {
            return arg.length;
        } else {
            return l_this.exists(arg) ? 1 : 0;
        }
    };

    this.parseInt = function(arg, base) {
    	if (arg === undefined) return undefined;
    	if (!arg) return 0;
    	if (l_this.isObject(arg)) return undefined;
    	if (!base) base = 10;
    	return parseInt(arg, base);
    }

    this.clone = function(arg) {
        return angular.copy(arg);  // TODO 第三者ライブラリーに依存せず、生JSから実装するのが望ましい
    };

    this.startsWith = function(a_str,a_search) {
    	if (!l_this.isString(a_str)) return undefined;
    	return a_str.lastIndexOf(a_search, 0) === 0;
    };

    this.endsWith = function(a_str,a_search) {
    	if (!l_this.isString(a_str)) return undefined;
    	return a_str.lastIndexOf(a_search) === a_str.length-a_search.length;
    };

    this.arrayAppend = function(a_array, a_add) {
    	if ( ! a_array ) a_array = [];
    	if (!l_this.isArray(a_array)) return undefined;
    	if (!l_this.isArray(a_add)) a_add = [a_add];
    	Array.prototype.push.apply(a_array, a_add);
    	return a_array;
    };

    this.arrayInsert = function(a_array, a_item) {
    	if ( ! a_array ) a_array = [];
    	if (!l_this.isArray(a_array)) return undefined;
    	a_array.unshift(a_item);
    	return a_array;
    };

	this.copyModel = function(a_src, a_dst, a_ignorePk) {
		l_this.copy(a_src, a_dst, a_ignorePk);
		a_dst.__model = a_src.__model;
		a_dst.__name = a_src.__name;
		return a_dst;
	}

    this.copy = function(a_src, a_dst, a_ignorePk) {
    	if (l_this.isArray(a_src)) {
			for (var i=0; i< a_src.length; i++) {
				var l_src = a_src[i];
				if (l_this.isObject(l_src)) {
					if (! a_dst[i]) {
						a_dst[i] = {};
					}
					l_this.copy(l_src,a_dst[i]);
				} else {
					a_dst[i] = l_src;
				}
			}
    	} else if (l_this.isObject(a_src)) {
    		var pkName = l_this.getPrimaryKeyName(a_dst);
        	for ( var l_key in a_src ) {
        		if (l_this.startsWith(l_key, "__")) continue;
        		if (a_ignorePk && l_key == pkName) continue;

        		var l_dst = a_dst[l_key];
        		var l_item = a_src[l_key];
        		if (l_this.isArray(l_item)) {
					if (! a_dst[l_key]) {
						a_dst[l_key] = [];
						l_dst = a_dst[l_key];
					}
        			l_this.copy(l_item,l_dst);
        		} else if (l_this.isObject(l_item)) {
					if (! a_dst[l_key]) {
						a_dst[l_key] = {};
					}
					l_dst = a_dst[l_key];
        			l_this.copy(l_item,l_dst);
        		} else {
        			a_dst = setObjectValue(a_dst,l_key,l_item);
        		}
        	}
    	}
    	return a_dst;
    };
    function setObjectValue(a_dst,a_key,a_src) {
    	if (! l_this.isObject(a_dst) ) {
    		a_dst = {};
    	}
    	a_dst[a_key] = a_src;
    	return a_dst;
    }

    this.clear = function(a_data) {
    	for ( var l_key in a_data ) {
    		if (l_this.startsWith(l_key,"__")) continue;
    		if (l_key == "queryContext") continue;
    		if (l_this.isFunction(a_data[l_key])) continue;

    		delete a_data[l_key];
    	}
    }

    this.getModel = function(a_data) {
    	if (a_data && a_data.__model) return a_data.__model;
    	return a_data;
    }

    this.getSubItems = function(a_model) {
    	a_model = l_this.getModel(a_model);
    	return a_model.sub_items;
    };
    this.getLabel = function(a_model) {
    	a_model = l_this.getModel(a_model);
    	function inner(a_model) {
    		if (! a_model) return null;
    		if (a_model.label) return a_model.label;
    		if (a_model.base_model) {
    			return inner(a_model.base_model);
    		}
    		return null;
    	}
    	return inner(a_model);
    };
    this.isAncestor = function(a_start,a_parent) {
    	a_start = l_this.getModel(a_start);
    	function inner(a_model) {
    		if (! a_model) return false;
    		if (a_model == a_parent) return true;
    		if (a_model.label == a_parent.label) return true;
    		if (a_parent == a_model.base_model) return true;
    		if (a_model.base_model) {
    			return inner(a_model.base_model);
    		}
    		return false;
    	}
    	return inner(a_start);
    };
    this.has = function(a_start,a_key) {
    	a_start = l_this.getModel(a_start);
    	function inner(a_model,a_key) {
    		if (! a_model) return undefined;
    		if (a_model[a_key] !== undefined) return a_model[a_key];
    		if (a_model.base_model) {
    			return inner(a_model.base_model,a_key);
    		}
    		return undefined;
    	}
    	return inner(a_start,a_key);
    };
    this.isMandatory = function(a_model) {
    	a_model = l_this.getModel(a_model);
    	function inner(a_model) {
    		if (!a_model) return false;
    		if (a_model.mandatory !== undefined) return a_model.mandatory;
    		if (a_model.base_model) {
    			return inner(a_model.base_model);
    		}
    		return false;
    	}
    	return inner(a_model);
    }
    this.isPrimaryKey = function(a_model) {
    	a_model = l_this.getModel(a_model);
    	function inner(a_model) {
    		if (!a_model) return false;
    		if (a_model.primary_key !== undefined) return a_model.primary_key;
    		if (a_model.base_model) {
    			return inner(a_model.base_model);
    		}
    		return false;
    	}
    	return inner(a_model);
    }
    this.getPrimaryKeyName = function(a_model) {
    	a_model = l_this.getModel(a_model);
		if (!a_model) return undefined;
    	var l_subItems = l_this.getSubItems(a_model);
    	for ( var l_key in l_subItems ) {
    		if ( l_this.isPrimaryKey(l_subItems[l_key]) ) {
    			return l_key;
    		}
    	}
    	return "recid";
    }
    this.getPrimaryKey = function(a_data) {
    	var l_model = l_this.getModel(a_data);
    	var l_keyName = l_this.getPrimaryKeyName(l_model);
    	return a_data[l_keyName];
    }
    this.isSuccess = function(a_data) {
    	if ( ! a_data.queryContext) return false;
    	if ( l_this.isArray(a_data.queryContext.errors) && a_data.queryContext.errors.length > 0) return false;
    	if ( l_this.isArray(a_data.queryContext.warnings) && a_data.queryContext.warnings.length > 0) return false;

    	return true;
    }

    /**
     * a_objの全アイテムにa_funcを再帰的に適用する
     * a_obj : 任意のオブジェクト
     * a_func : a_func(a_key,a_item,a_parent)
     *     a_key : アイテム名
     *     a_item : アイテム
     *     a_parent : 親クラス
     */
    this.traverse = function(a_obj, a_func) {
    	function inner(a_obj, a_func, a_parent, a_key) {
        	if ( l_this.isArray(a_obj) ) {
    			for ( var l_idx=0; l_idx < a_obj.length; l_idx++ ) {
    				var l_item = a_obj[l_idx];
    				inner(l_item,a_func,a_obj,l_idx);
    			}
        	} else if ( l_this.isObject(a_obj) ) {
            	for ( var l_key in a_obj ) {
            		var l_item = a_obj[l_key];
            		inner(l_item, a_func,a_obj,l_key);
            	}
        	} else {
    			a_func(a_key,a_obj,a_parent);
        	}
    	}
    	inner(a_obj, a_func);
    };

    this.getToday = function() {
    	return l_this.getYmd(new Date());
    }
    this.getYmd = function(arg) {
    	if (!arg || !arg.getTime) return undefined;
    	return arg.getFullYear()+'-'+l_this.to2keta(arg.getMonth()+1)+'-'+l_this.to2keta(arg.getDate());
    }
    this.getHms = function(arg) {
    	if (!arg || !arg.getTime) return undefined;
    	return l_this.to2keta(arg.getHours())+':'+l_this.to2keta(arg.getMinutes())+':'+l_this.to2keta(arg.getSeconds());
    }
    this.getHourMinute = function(arg) {
    	if (!arg || !arg.getTime) return undefined;
    	return l_this.to2keta(arg.getHours())+':'+l_this.to2keta(arg.getMinutes());
    }
    this.getDateMilliSec = function(arg) {
    	var ymd = arg;
    	if (arg.nengo) {
    		ymd = l_this.convertFromJapaneseDate(arg.nengo,arg.year,arg.month,arg.day);
    	} else if (arg.getTime) {
    		return arg.getTime();
    	}
		return Date.parse(ymd);
    }
    this.splitMilliSec = function(arg) {
    	if (!arg || arg == undefined) return undefined;
	return arg.substr(0,arg.indexOf("."));
    }
    this.diffnull = function(arg) {
    	if (arg == undefined || !arg || arg == "") return true;
	return false;
    }
    this.diff = function(arg1,arg2) {
    	if (this.diffnull(arg1) && this.diffnull(arg2) ) return true;

	return arg1 == arg2 ;
    }
    this.diff2 = function(arg1,arg2,word) {
    	var rtn =  this.diff(arg1,arg2) ? '' : word ;
    	return rtn;
    }
    this.diff4 = function(arg1,arg2,arg3,arg4,word) {
    	var rtn =  (this.diff(arg1,arg2) && this.diff(arg3,arg4)) ? '' : word ;
    	return rtn;
    }
    this.diff6 = function(arg1,arg2,arg3,arg4,arg5,arg6,word) {
    	var rtn =  (this.diff(arg1,arg2) && this.diff(arg3,arg4) && this.diff(arg5,arg6)) ? '' : word ;
    	return rtn;
    }
    this.DNO = function(arg1,arg2) {
    	//if (!this.diffnull(arg1) || !this.diffnull(arg2) ) return "";

	if ( arg1 == undefined ) return "";
	if ( arg1 == 0 ) return "";
	if ( arg1 > 9 )  return arg1+"-"+arg2;

	return "0"+arg1+"-"+arg2;
    }
    this.calcAge = function(a_birthday) {
    	//return Math.floor( (l_this.getDateMilliSec(l_this.getToday()) - l_this.getDateMilliSec(a_birthday)) / (1000*3600*24*365) );
    	var today = new Date();
    	var birthday = a_birthday;
    	if (l_this.isString(birthday)) {
    		birthday = l_this.convertToJapaneseDate(birthday);
    	}
    	if ( birthday && birthday.nengo ) {
    		birthday = l_this.convertFromJapaneseDate(birthday);
    	}
    	if (l_this.isString(birthday)) {
    		birthday = new Date(birthday);
    	}
    	if (! birthday) return Number.NaN;
    	if (! birthday.getFullYear) return Number.NaN;

    	var age = today.getFullYear () - birthday.getFullYear();
    	var month = today.getMonth();
    	var bmonth = birthday.getMonth();
    	if (month > bmonth) return age;

    	var day = today.getDate();
    	var bday = birthday.getDate();
    	// ×満年齢は誕生日の前日に加算される (明治三十五年法律第五十号（年齢計算ニ関スル法律）)
	// 〇本システムは0時起点で当日加算の実年齢とする。上記は厳密にいうと前日の12時のこと
    	if (month == bmonth && bday - day < 1) return age;

    	// 今年の誕生日が来ていない場合
    	return age - 1;
    }

    this.getTimestamp = function(a_ymdhms) {
    	if (!a_ymdhms || a_ymdhms.year) return a_ymdhms;
    	if (!l_this.isString(a_ymdhms)) return undefined;
    	a_ymdhms = a_ymdhms.replace('/','-');
    	a_ymdhms = a_ymdhms.replace('T',' ');
    	var ymdhms = a_ymdhms.replace(' ','T');
    	var dt = ymdhms.split('T');
    	var d = dt[0].split('-');
    	var t = (dt.length > 0) ? dt[1].split(':') : ['00','00','00'];
    	return {
    		__model : this.app.models.timestamp,
    		__name : "timestamp",
    		year  : d[0],
    		month : d[1],
    		day   : d[2],
    		hour : t[0],
    		min  : t[1],
    		sec  : t[2],
    		value : a_ymdhms
//    		value : ymdhms
    	};
    }

    this.getEnglishDate = function(arg, order) {
    	if (!arg || !arg.getTime) return undefined;
    	var l_weeks = ['Sun,', 'Mon,', 'Tue,', 'Wed,', 'Thu,', 'Fri,', 'Sat,'];
    	var l_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    	var l_year = arg.getFullYear();
    	var l_month = arg.getMonth();
    	var l_week = arg.getDay();
    	var l_day = arg.getDate();
    	if (order) {
        	return l_weeks[l_week] + ' ' + l_months[l_month] + ' ' + l_day + ' ' + l_year;
    	} else {
        	return l_weeks[l_week] + ' ' + l_day + ' ' + l_months[l_month] + ' ' + l_year;
    	}
    }

    this.initJapaneseDate = function(arg) {
     	arg.jvalue = '';
    	arg.value = '';
    	arg.nengo = '--';
	arg.year  = '';
    	arg.month = '';
	arg.day = '';

	return;
    }
    this.isJapaneseDate = function(arg) {
    	if (!arg) return false;

    	if (!arg.nengo || arg.nengo == "") return false;
    	if (!arg.year || arg.year == "") return false;
    	if (!arg.month || arg.month == "") return false;
    	if (!arg.day || arg.day == "") return false;

	return true;
    }

    this.remainDays = function(arg) {
    	if (!arg) return undefined;

    	if (arg.nengo) {
    		arg = l_this.convertFromJapaneseDate(arg.nengo, arg.year, arg.month, arg.day);
    	}
    	var date = new Date(arg);
    	var today = new Date();
    	var diff = date.getTime() - today.getTime();
    	return Math.round(((diff/1000)/3600)/24);
    }

    /** 和暦の年号（元号） */
    var nengos = [
             		{"key": 'M', "label": "明治", "start":"1868-01-25" },
             		{"key": 'T', "label": "大正", "start":"1912-07-30" },
             		{"key": 'S', "label": "昭和", "start":"1926-12-25" },
             		{"key": 'H', "label": "平成", "start":"1989-01-08" },
             		{"key": 'G', "label": "元号", "start":"2019-05-01" },// TODO:新元号 仮(G:元号 2019-05-01)対応
                 ];
    /** 実際に画面で使われる年号 */
    this.nengoNames = [{k:'--',n:"--"},{k:'S',n:"昭和"},{k:'H',n:"平成"},{k:'G',n:"元号"},];

    function findNengoByKey(a_key) {
    	for ( var l_idx = 0; l_idx < nengos.length; l_idx++ ) {
    		var nengo = nengos[l_idx];
    		if ( nengo.key == a_key ) return nengo;
    	}
    	return null;
    };
    function findNengoByLabel(a_key) {
    	for ( var l_idx = 0; l_idx < nengos.length; l_idx++ ) {
    		var nengo = nengos[l_idx];
    		if ( nengo.label == a_key ) return nengo;
    	}
    	return null;
    };
    this.findNengoByLabel = function(a_key) {
    	return findNengoByLabel(a_key);
    }
    function findNengoByDate(a_ymd) {
    	for ( var l_idx = nengos.length - 1; l_idx >= 0 ; l_idx-- ) {
    		var nengo = nengos[l_idx];
    		if ( nengo.start <= a_ymd ) return nengo;
    	}
    	return null;
    };

    this.setJapaneseNengos = function(app) {
    	app.nengos = nengos;
    	return nengos;
    };
	this.convertFromJapaneseYear = function(a_nengo,a_year) {
    	var l_nengo = findNengoByKey(a_nengo);
    	if (!l_nengo) l_nengo = findNengoByLabel(a_nengo);
    	var l_startYear = l_nengo.start.split('-')[0];
		var l_yearVal =  l_this.parseInt(a_year);
		if (isNaN(l_yearVal)) {
			return a_year;
		}
    	var l_year = l_startYear - 1 + l_yearVal;
		return l_year;
	};
    this.convertFromJapaneseDate = function(a_nengo,a_year,a_month,a_day) {
    	if (l_this.isObject(a_nengo) && a_nengo.nengo) {
    		var jdate = a_nengo;
    		a_nengo = jdate.nengo;
    		a_year  = jdate.year;
    		a_month = jdate.month;
    		a_day   = jdate.day;
    	}
    	if ( !a_nengo || !a_year || !a_month || !a_day) return undefined;
    	if ( a_nengo == '--' || a_year == '--' || a_month == '--' || a_day == '--') return undefined;
    	var l_year = l_this.convertFromJapaneseYear(a_nengo, a_year);
     	var l_value = l_year + '-' + l_this.to2keta(a_month) + '-' + l_this.to2keta(a_day);
     	return l_value;
    };
    this.convertToJapaneseDate = function(a_date) {
    	if (!a_date) return a_date;

    	var l_date = a_date;
    	if ( l_this.isObject(l_date) ) {
    		if (l_date.value) {
    			l_date = l_date.value;
    		} else if (l_date.getFullYear) {
    			l_date = l_this.dateFormat(l_date, 'YYYY-MM-DD');
    		}
    	}
    	if ( !l_date || !l_this.isString(l_date) ) {
    		if (l_this.startsWith(l_date,"NaN")) {
    			return undefined;
    		} else {
//        		return {nengo:'--',year:'--',month:'--',day:'--'};
        		return {nengo:'--',year:'',month:'',day:''};
    		}
    	}
    	l_date = l_date.split(' ')[0].split('T')[0];

    	var l_jdate = { value: l_date };
    	var l_arr = l_date.split('-');
    	var l_nengo = findNengoByDate(l_date);
    	if (!l_nengo) {
    		return null;
    	}

     	var l_year  = l_arr[0];
     	var l_month = l_this.to2keta(l_arr[1]);
     	var l_day   = l_this.to2keta(l_arr[2]);
     	var l_startYear = l_nengo.start.split('-')[0];
     	if ( l_nengo && l_this.isNumeric(l_year) ) {
     		l_jdate.nengo = l_nengo.key;
         	l_jdate.year = l_this.to2keta("" + (l_year - l_startYear + 1));
     	} else {
     		l_nengo = l_year.substr(0,1);
     		l_nengo = findNengoByKey(l_nengo);
     		if (l_nengo) {
         		l_jdate.year = l_this.to2keta(l_year.substr(1));
     			l_jdate.nengo = l_nengo.key;
     		} else {
     			l_jdate.year = l_year;
     		}
     	}
     	l_jdate.month = l_month;
     	l_jdate.day = l_day;
     	var l_nengoLabel = l_nengo ? l_nengo.label : '  ';
     	l_jdate.jvalue = l_nengoLabel+l_jdate.year+'年'+l_month+'月'+l_day+'日';
    	l_jdate.value = (l_nengo ? l_nengo.key : ' ')+l_jdate.year+'-'+l_month+'-'+l_day;
     	return l_jdate;
    };
    this.convertToJapaneseTime = function(a_time) {
    	if (!a_time) return a_time;
    	var l_array = a_time.split(':');
    	return l_this.to2keta(l_array[0])+'時'+l_this.to2keta(l_array[1])+'分';
    };
    this.to2keta = function(a_val) {
    	return l_this.fill_prefix(a_val,2,'0');
    };
    this.getNowDateTimeJp = function() {
    	var l_date = new Date();
    	var l_ymd = l_this.getYmd(l_date);
    	var l_time = l_this.getHms(l_date);
    	var l_jdate = l_this.convertToJapaneseDate(l_ymd);
    	var l_jtime = l_this.convertToJapaneseTime(l_time);
    	if (!l_jdate || !l_jtime) return "";
    	return l_jdate.jvalue + l_jtime;
    };
    this.dateFormat = function(a_date, a_format) {
    	var l_value = a_format.replace(/YYYY/g, a_date.getFullYear());
    	l_value = l_value.replace(/MM/g, l_this.to2keta(a_date.getMonth()+1));
    	l_value = l_value.replace(/DD/g, l_this.to2keta(a_date.getDate()));
    	return l_value;
	};
    this.repeat = function(a_val, a_length) {
    	if (a_val.repeat) return a_val.repeat(a_length);
    	var l_ret = '';
    	for (var i = 0; i < a_length; i++) {
    		l_ret += a_val;
    	}
    	return l_ret;
    };

    /**
     * 指定文字数になるまで指定文字で前詰めにする
     */
    this.fill_prefix = function(a_str,a_length,a_fill) {
    	if (a_str === undefined || a_str === null) return a_str;
    	a_str = '' + a_str;
    	if ( !a_str || a_str.length >= a_length ) return a_str;
    	var l_str = l_this.repeat(a_fill, a_length-a_str.length)+a_str;
    	return l_str;
    };

    /**
     * クッキーをセットする
     * a_name : クッキー名
     * a_value : クッキー値
     * a_seconds : 存在秒数
     * a_expires : 有効期限
     * a_path : 対象パス
     */
    this.setCookie = function(a_name, a_value, a_seconds, a_expires, a_path) {
    	var l_cookie = a_name+"="+a_value;
    	if (a_seconds) l_cookie += "; max-age="+a_seconds;
    	if (a_expires) l_cookie += "; expires="+a_expires;
    	if (a_path)  l_cookie += "; path="+a_path;
    	document.cookie = l_cookie;
    	return document.cookie;
    };
    /**
     * クッキーから値を取得する
     * a_name : クッキー名
     * return : クッキー値
     */
    this.getCookie = function(a_name) {
    	var l_arr = document.cookie.split(';');
    	for ( var i = 0; i < l_arr.length; i++ ) {
    		var l_arr2 = l_arr[i].split('=');
    		if (! l_arr2 || !l_arr2[0] || !l_arr2[1]) continue;
    		var l_name = l_arr2[0].trim();
    		var l_value = l_arr2[1].trim();
    		if (l_name == a_name) return l_value;
    	}
    	return undefined;
    };
    /**
     * クッキーから値を削除する
     * a_name : クッキー名
     * a_path : 対象パス
     */
    this.delCookie = function(a_name,a_path) {
    	l_this.setCookie(a_name, "", undefined, new Date("1900/01/01").toUTCString(),a_path);
    	return document.cookie;
    };

    /**
     * ウィンドウ内部枠の横幅を返す
     */
    this.windowWidth = function() {
    	return window.innerWidth ? window.innerWidth: $(window).width();
    };
    /**
     * ウィンドウ内部枠の高さを返す
     */
    this.windowHeight = function() {
    	return window.innerHeight ? window.innerHeight: $(window).height();
    };

    /**
     * '#'+a_idにサイズを設定する
     */
    this.setSize = function(a_id, a_width, a_height) {
    	$('#'+a_id).width(a_width);
    	$('#'+a_id).height(a_height);
    };
    /**
     * '#'+a_idに表示位置を設定する
     */
    this.setPos = function(a_id, a_left, a_top) {
    	$('#'+a_id).css('left',a_left);
    	$('#'+a_id).css('top',a_top);
    };

    /**
     * 名称を配列で渡すと列挙型を返す。
     * enum.m_names[num]でnum番目の名称が得られる。
     * a_names : 名称の配列
     * return : 名称をフィールド名に持ったオブジェクト。フィールドの値は通番。
     */
    this.getEnum = function(a_names) {
    	var l_enum = {m_names:a_names};
    	for (var i in a_names) {
    		var name = a_names[i];
    		l_enum[name] = i;
    	}
    	return l_enum;
    };
    
    this.intVal = function(a_val) {
    	if (a_val === undefined) return 0;
    	var l_str = a_val+"";
    	return parseInt(l_str);
    }

});
