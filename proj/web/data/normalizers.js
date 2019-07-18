/*
 * "data/normalizers.js"
 */
angular.module("nispApp").service("Normalizers", ["groundwork", function(gw) {
    "use strict";

    var l_this = this;

    var l_number = '0123456789';
    var l_numberZen = '０１２３４５６７８９';
    var l_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var l_upperZen = 'ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺ';
    var l_lower = l_upper.toLowerCase();
    var l_lowerZen = 'ａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ';
    var l_mark = ' !"#$%&\'()-=^~\\|@`[{;+:*]},<.>/?_';
    var l_markZen = '　！”＃＄％＆’（）－＝＾～￥｜＠`［｛；＋：＊］｝，＜．＞／？＿';
    var l_jMark = 'ｰ ､｡･｢｣';
    var l_jMarkZen = 'ー　、。・「」';
    var l_han = {
    	    org : 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝｧｨｩｪｫｯｬｭｮ',
    	    dakuOrg    : 'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ',
    	    daku       : 'ｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾊﾋﾌﾍﾎ',
    	    handakuOrg : 'ﾊﾋﾌﾍﾎ',
    	    handaku    : 'ﾊﾋﾌﾍﾎ',
    };
    var l_hira = {
    	    org : 'あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわをんぁぃぅぇぉっゃゅょ',
    	    dakuOrg : 'かきくけこさしすせそたちつてとはひふへほ',
    	    daku    : 'がぎぐげござじずぜぞだぢづでどばびぶべぼ',
    	    handakuOrg : 'はひふへほ',
    	    handaku    : 'ぱぴぷぺぽ',
    };
    var l_kata = {
    	    org : 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンァィゥェォッャュョ',
    	    dakuOrg : 'カキクケコサシスセソタチツテトハヒフヘホ',
    	    daku    : 'ガギグゲゴザジズゼゾダヂヅデドバビブベボ',
    	    handakuOrg : 'ハヒフヘホ',
    	    handaku    : 'パピプペポ',
    };
    var l_dakuten = 'ﾞ';
	var l_dakutenZen = '゛';
    var l_hanDakuten = 'ﾟ';
    var l_hanDakutenZen = '゜';

    /*
     * 半角英数字記号に揃える
     */
    this.hankaku = function() {
    	var l_str = '';
    	l_str = convertAll(this,l_numberZen,l_number);
    	l_str = convertAll(l_str,l_upperZen,l_upper);
    	l_str = convertAll(l_str,l_lowerZen,l_lower);
    	l_str = convertAll(l_str,l_markZen,l_mark);
    	return l_str;
    };

    /*
     * 全角ひらがなに揃えるl
     */
    this.hiragana = function() {
    	//全角カタカナに揃える
    	var l_str = toZenkaku(this,l_han,l_kata);
    	l_str = toZenkaku(l_str,l_hira,l_kata);
    	//ひらがなにする
    	return toZenkaku(l_str,l_kata,l_hira);
    };

    /*
     * 全角カタカナに揃える
     */
    this.katakana = function() {
    	var l_str = toZenkaku(this,l_han,l_kata);
    	return toZenkaku(l_str,l_hira,l_kata);
    };

    /*
     * 和暦をY-M-D形式に変換する
     */
    this.japanese_ymd = function() {
    	var l_jdate = this;
    	if ( gw.isString(l_jdate) ) {
    		var l_nengo = l_jdate.charAt(0);
    		if (l_nengo >= 'z') {
    			l_nengo += l_jdate.charAt(1);
    			l_jdate = l_jdate.substr(2);
    			l_jdate = l_jdate.replace('年','-').replace('月','-').replace('日','');
    		} else {
    			l_jdate = l_jdate.substr(1);
    		}
    		var l_arr = l_jdate.split('-');
    		return gw.convertFromJapaneseDate(l_nengo,l_arr[0],l_arr[1],l_arr[2]);
    	}
    	l_jdate.month = gw.fill_prefix(l_jdate.month,2,'0');
    	l_jdate.day = gw.fill_prefix(l_jdate.day,2,'0');
    	l_jdate.value = gw.convertFromJapaneseDate(l_jdate);
     	return l_jdate;
    };
    /**
     * Y-M-D形式を和暦に変換する
     */
    this.ymd_japanese = function() {
    	var l_date = gw.convertToJapaneseDate(this);
    	if (!gw.isObject(l_date)) return l_date;
    	return l_date;
    };

    this.zero2_fill = function() {
    	return gw.fill_prefix(this,2,'0');
    };

    this.time_japanese = function() {
    	var l_array = this.split(':');
    	var l_str = "";
    	for (var i in l_array) {
    		l_array[i] = l_this.zero2_fill.apply(l_array[i]);
    	}
    	if ( l_array.length > 0 ) l_str += l_array[0] + "時";
    	if ( l_array.length > 1 ) l_str += l_array[1] + "分";
    	if ( l_array.length > 2 ) l_str += l_array[2] + "秒";
    	return l_str;
    }

    function createTimestamp(a_str) {
    	if (!a_str) return a_str;
    	if (gw.isObject(a_str) && a_str.value) {
    		a_str = a_str.value;
    	}
    	if (!a_str) return a_str;

    	var dateTime = a_str.split(' ');
    	if (dateTime.length == 1) dateTime = a_str.split('T');
    	if (dateTime.length == 1) dateTime = [a_str,"00:00:00.000"];

    	return join(dateTime,'T');
    }
    function createJtimestamp(a_str) {
    	if (!a_str) return a_str;
    	if (gw.isObject(a_str)) {
    		a_str = a_str.value;
    	}
    	if (!a_str) return a_str;

    	var dateTime = a_str.split(' ');
    	if (dateTime.length == 1) dateTime = a_str.split('T');
    	if (dateTime.length == 1) dateTime = [a_str,"00:00:00.000"];
    	var jdate = dateTime[0];
    	var l_timestamp = l_this.ymd_japanese.apply(jdate);
    	var jtime = dateTime[1].split(':');
    	l_timestamp.hour = jtime[0];
    	l_timestamp.min = jtime[1];
    	var msec = jtime[2].split('.');
    	l_timestamp.sec = msec[0];
    	l_timestamp.msec = (msec.length > 1) ? msec[1] : "";
    	l_timestamp.value += ' '+dateTime[1];
//    	l_timestamp.jvalue += ' '+l_timestamp.hour+"時"+l_timestamp.min+"分"+l_timestamp.sec+"秒"+l_timestamp.msec;
    	l_timestamp.jvalue += ' '+l_timestamp.hour+"時"+l_timestamp.min+"分"+l_timestamp.sec+"秒";
    	return l_timestamp;
    }

    this.timestamp_japanese = function() {
    	var l_timestamp = this;
    	if (gw.isString(this)) {
    		l_timestamp = createJtimestamp(this);
    	} else if (this && this.value) {
    		l_timestamp = createJtimestamp(this.value);
    	}
    	return l_timestamp;
    }
    this.japanese_timestamp = function() {
    	var l_timestamp = this;
    	if (gw.isString(this)) {
    		var l_nengo = gw.findNengoByLabel(this.substr(0,2)).key;
    		var l_str = l_nengo + this.substr(2);
    		var l_str = l_str.replace('年','-').replace('月','-').replace('日','');
    		l_str = l_str.replace('時',':').replace('分',':').replace('秒','.');
    		if (gw.endsWith(l_str,'.')) l_str = l_str.substr(0,l_str.length-1);
    		l_timestamp = createJtimestamp(l_str);
    	} else {
    		l_timestamp = l_this.japanese_ymd.apply(l_timestamp);
    	}
    	if (!l_timestamp || !l_timestamp.value) return l_timestamp;

		var l_time = l_timestamp.value.split(' ')[1];
		if (!l_time) l_time = "00:00:00";
		l_timestamp.value = gw.convertFromJapaneseDate(l_timestamp) + ' ' + l_time;
    	return l_timestamp;
    }

    this.to_ymdhms = function() {
    	var l_value = this;
    	if (!l_value) return l_value;
    	if (gw.isObject(l_value)) {
    		if (l_value.value) {
    			l_value.value = l_value.value.replace(' ','T');
    			return l_value.value;
    		}
    		l_value = timestamp2Ymd(l_value);
    		if (!l_value) return l_value;
    	}
    	l_value = l_value.replace('/','-');
    	var tm = l_value.replace(' ','T');
    	if (tm == l_value) {
    		tm += 'T00:00:00';
    	}
    	return tm;
    }
    this.from_ymdhms = function() {
    	var l_value = this;
    	if (!l_value) return l_value;

    	if (gw.isObject(l_value)) {
    		if (l_value.year) return l_value;
    		if (l_value.value) {
    			l_value = l_value.value;
    		}
    		l_value = gw.getTimestamp(l_value);
    	}
   		return l_value;
    }
    function timestamp2Ymd(value) {
    	if (!value) return "";
    	if (value.year) {
    		var ymd = value.year+'-'+value.month+'-'+value.day+' ';
    		ymd += (value.hour) ? (value.hour+':'+value.min+':'+value.sec) : "00:00:00";
    		return ymd;
    	}
    	if (!value.getDate) return "";
    	return gw.getYmd(value)+' '+gw.getHms(value);
    }

    this.from_ymd = function() {
//    	if (!this || !this.year || !this.month || !this.day) return {year:"", month:"", day:""};
    	if (!this) return "";
    	var value = this;
    	if (!gw.isString(value)) {
    		if (!value.value) return this;
    		value = value.value;
    	}
    	var l_arr = value.split('-');
    	return {year:l_arr[0], month:l_arr[1], day: l_arr[2], value: value,};
    }
    this.to_ymd = function() {
    	if (!this) return "";
    	if (gw.isString(this)) return this;
    	if (!gw.isObject(this)) return this;
    	if (!this.year || !this.month || !this.day) return this.value;
    	return this.year+'-'+gw.to2keta(this.month)+'-'+gw.to2keta(this.day);
    }

    var kengen_names = ["roleSystem","roleKojin","rolePublic","roleMatch","roleIncident"];

    /**
     * 権限CDのjvalueを削除する
     */
    this.roles = function() {
    	if (! gw.isObject(this)) return this;
    	delete this.jvalue;
    	for ( var l_key in this) {
    		if (! this[l_key] || this[l_key] == "undefined") this[l_key] = undefined;
    	}
    	return this;
    }

    /**
     * 権限CDを個別のフィールドと表示用の文字列にする
     */
    this.roles_jp = function() {
    	this.jvalue = "";
    	for (var l_idx=0; l_idx<kengen_names.length; l_idx++) {
    		var l_key = kengen_names[l_idx];
    		var l_value = this[l_key];
    		if (l_value == 0 || l_value == "0" || l_value == "undefined") {
    			l_value = undefined;
    			this[l_key] = undefined;
    		} else if (l_value !== undefined) {
    			this[l_key] = '' + l_value;
    		}
    		var l_name = gw.app.models.constants.find(l_key,l_value);
    		this.jvalue += l_name ? l_name.charAt(0) : "-";
    	}
    	return this;
    }

    this.userId = function() {
    	if (!gw.isString(this)) return this;
    	var l_array = this.split(';');
    	return l_array.length > 1 ? l_array[1] : this;
    }
    this['$_user_id'] = this.userId;

    /**
     * 年齢を返す(項目、ViewModel全体どちらも可)
     */
    this.age = function() {
    	if (this.birthday)
    		this.age = gw.calcAge(this.birthday);
    	else if (! this.__model)
    		this.age = gw.calcAge(this);
    	return this;
    }
    this["$_age"] = this.age;

    /**
     * 文字列を'-'でcode1,code2, ...に切り分ける
     */
    this.split = function() {
    	var str = this+"";
    	var vals = str.split('-');
    	if (vals.length <= 1) return this;
    	var obj = {};
    	var n = 1;
    	for (var key in vals) {
        	obj["code"+n] = vals[key];
        	n++;
    	}
    	return obj;
    }
    /**
     * code1,code2, ...を'-'でつなぐ
     */
    this.join = function() {
    	var str = "";
    	if ( Object.keys(this).length == 0 ) return this;

    	var i = 0;
    	for (var key in this) {
        	if (i++ == 0) {
        		str = this[key];
        	} else {
        		str += "-"+this[key];
        	}
    	}
    	return str;
    }

    this.getUserId = function() {
    	if (!this) return this;
    	var arr = this.split(':');
    	if (arr.length>1) return arr[1];
    	return this;
    }

    this.split_slash = function() {
    	return split(this,'-');
    }
    this.join_slash = function() {
    	return join(this,'/');
    }

    this.split_hyphen = function() {
    	return split(this,'-');
    }
    this.join_hyphen = function() {
    	return join(this,'-');
    }

    this.split_colon = function() {
    	return split(this,':');
    }
    this.join_colon = function() {
    	var ret = join(this,':');
    	return ret;
    }

    this.split_2keta = function() {
    	if (!this) return ['', ''];
    	if (gw.isArray(this)) return this;
    	if (this.indexOf(':') >= 0)
    		return this;
    	var l_array = [];
    	for (var i = 0; i < this.length; i += 2) {
    		l_array.push(this.substr(i,2));
    	}
    	return l_array;
    }
    this.join_2keta = function() {
    	if (!this) return "";
    	if (gw.isString(this)) return this;
    	var l_str = "";
    	for (var i = 0; i < this.length; i++) {
            // if (this[i] == "") this[i] = "0"; // New fix for int
    		l_str += gw.fill_prefix(this[i],2,'0');
    	}
    	return l_str;
    }

    this.image_url = function() {
    	if (gw.isString(this) && gw.startsWith(this, "http")) return this;
    	if (!this) return this;
    	var url = gw.app.fn_getFileApiUrl()+"/filesrv/FileApi?fileId="+this;
    	return url;
    }
    this.image_seq = function() {
    	if (!gw.isString(this) || !gw.startsWith(this, "http")) return this;
    	var url = this.split("=")[1];
    	return url;
    }

    this.address = function() {
    	return gw.app.models.constants.find('prefCd', this.prefCd)+this.shozai;
    }

    this.set_center_name = function() {
		for (var i in this.entries) {
			this.entries[i].centerName = gw.app.centers[this.entries[i].centerId];
		}
	}

    //// UTILS. ////

    /**
     * 全角に揃える
     */
    function toZenkaku(a_str,a_from,a_to) {
    	if ( ! gw.exists(a_str) ) return a_str;
    	if ( ! gw.exists(a_str.length) ) return a_str;

    	var l_str = '';
    	for (var i = 0; i < a_str.length; i++ ) {
    		var l_char = a_str.charAt(i);
    		var l_org = l_char;
    		l_char = convert(l_char,a_from.org,a_to.org);
    		if ( l_char == l_org ) {
    			l_char = convert(l_char,l_jMark,l_jMarkZen);
    			l_char = convert(l_char,a_from.daku,a_to.daku);
    			l_char = convert(l_char,a_from.handaku,a_to.handaku);
    			l_str += l_char;
    		} else {
        		var l_next = a_str.charAt(i+1);
        		if ( l_next == l_dakuten) {
        			l_str += convert(l_org,a_from.dakuOrg,a_to.daku);
        			i++;
        		} else if ( l_next == l_hanDakuten ) {
        			l_str += convert(l_org,a_from.handakuOrg,a_to.handaku);
        			i++;
        		} else {
        			l_str += l_char;
        		}
    		}
    	}
    	return l_str;
    }

    function convertAll(a_str,a_from,a_to) {
    	if (gw.isNumeric(a_str)) return a_str;
    	if ( ! a_str ) return a_str;
    	var l_str = '';
    	for (var i = 0; i < a_str.length; i++ ) {
    		var l_char = a_str.charAt(i);
    		l_str += convert(l_char,a_from,a_to);
    	}
    	return l_str;
    }
    function convert(a_char,a_from,a_to) {
    	if ( gw.isObject(a_from) ) {
    		for ( var l_key in a_from ) {
    			var l_from = a_from[l_key];
    			var l_to;
    			if ( gw.isObject(a_to) ) {
        			if ( ! gw.exists(a_to[l_key]) ) continue;
        			l_to = a_to[l_key];
    			} else {
    				l_to = a_to;
    			}
    			a_char = convert(a_char,l_from,l_to);
    		}
    		return a_char;
    	}
    	var l_idx = a_from.indexOf(a_char);
    	return (l_idx < 0) ? a_char : a_to.charAt(l_idx);
    }

    function split(a_val,a_splitter) {
    	if (!a_val || !gw.isString(a_val)) return a_val;
    	var arr = a_val.split(a_splitter);
    	if (arr.length == 1) return ['',''];
    	return arr;
    }
    function join(a_val,a_connector) {
    	if (!a_val || gw.isString(a_val)) return a_val;
    	var str = "";
    	for ( var i in a_val ) {
    		if (str) str += a_connector;
    		str += a_val[i];
    	}
    	return str;
    }
}]);
