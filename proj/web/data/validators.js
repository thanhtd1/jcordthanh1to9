/*
 * "data/validators.js"
 */
angular.module("nispApp").service("Validators", ["groundwork", function(gw) {
    "use strict";

    var l_this = this;

    /*
     * integer
     */
    this.integer = function() {
    	if (this === undefined || this === null) return true;
        if (Math.round(this) === this) {  // 既にIntegerである
            return true;
        } else {
            return /^(\-|\+)?([0-9]+)$/.test(this);  // 整数を表す文字列である
        }
    }

    /*
     * boolean
     */
    this.boolean = function() {
    	if (this === undefined || this === null) return true;
    	if (this === true || this === false) {
    		return true;
    	} else {
    		return (this === "true" || this === "false");
    	}
    }

    /*
     * decimal
     */
    this.decimal = function() {
    	if (this === undefined || this === null) return true;
        if(isFinite(this)){
            return true;
        } else {
            return /^(\-|\+)?([0-9]+)$/.test(this);  // 実数を表す文字列である
        }
    }

    /*
     * date
     */
    this.date = function() {
    	if (this === undefined || this === null) return true;

    	var l_str = this.replace(/\//g, "-");
    	var l_date = new Date( l_str );
        if( isNaN(l_date.getFullYear()) ) {
            return false;
        }
        var l_ymd = gw.getYmd(l_date);
        var l_edate = gw.getEnglishDate(l_date);
        var l_edate2 = gw.getEnglishDate(l_date, true);
        if ( !gw.startsWith(l_str, l_ymd) && !gw.startsWith(l_str, l_edate) && !gw.startsWith(l_str, l_edate2) ) {
        	return false;
        }
        return true;  // {#495} validatorの拡充
    }

    /*
     * timestamp
     */
    this.timestamp = function() {
        return l_this.date.apply(this);  // {#495} validatorの拡充
    }

    /**
     * japanese date
     */
    this.japanese_date = function() {
    	if (this === undefined || this === null) return true;
    	var l_jdate = gw.isString(this) ? gw.convertToJapaneseDate(this) : this;
    	if (! l_jdate.nengo) return false;
    	if (! l_jdate.year) return false;
    	if (! l_jdate.month) return false;
    	if (! l_jdate.day) return false;
    	return l_this.date.apply(gw.convertFromJapaneseDate(l_jdate));
    }

    function getItem(a_item,a_model) {
    	if (gw.isString(a_item) && a_model && a_model.parent && a_model.parent.realData) {
    		var l_item = a_model.parent.realData[a_item];
    		return l_item;
    	}
        return undefined;
    }
    function getLeftRight(a_data,a_item,a_model,a_pair) {
    	if (a_data === undefined || a_data === null) return true;

    	a_item = getItem(a_item,a_model);
    	if (a_item === undefined || a_item === null) return true;

    	a_pair.lhs = (a_data.value !== undefined) ? a_data.value : a_data;
    	a_pair.rhs = (a_item.value !== undefined) ? a_item.value : a_item;
    	return false;
    }

    /**
     * 指定項目より大なり
     */
    this.bigger_than = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs > l_pair.rhs;
    }
    /**
     * 指定項目より小なり
     */
    this.littler_than = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs < l_pair.rhs;
    }
    /**
     * 指定項目以上
     */
    this.bigger_equals = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs >= l_pair.rhs;
    }
    /**
     * 指定項目以下
     */
    this.littler_equals = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs <= l_pair.rhs;
    }
    /**
     * 指定項目と等しい
     */
    this.equals_with = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs == l_pair.rhs;
    }
    /**
     * 指定項目と異なる
     */
    this.different_from = function(a_item,a_model) {
    	var l_pair = {};
    	if (getLeftRight(this,a_item,a_model,l_pair)) return true;
    	return l_pair.lhs != l_pair.rhs;
    }

    this.equals_any = function(a_range) {
    	for (var i in arguments) {
    		if (arguments[i] == this) {
    	    	return true;
    		}
    	}
    	return false;
    }
    this.equalsany = function(a_range) { return l_this.equals_any.apply(this, arguments); }

    /*
     * 最大長
     */
    this.max_length = function(a_maxLength) {
    	if (!this) return true;
        if (!gw.isString(this)) {
            return false;
        } else {
            return this.length <= a_maxLength;
        }
    }
    this.maxlength = function(a_maxLength) { return l_this.max_length.apply(this,[a_maxLength]); }

    /*
     * 最小長
     */
    this.min_length = function(a_minLength) {
        if (!gw.isString(this)) {
            return false;
        } else {
            return this.length >= a_minLength;
        }
    }
    this.minlength = function(a_minLength) { return l_this.min_length.apply(this,[a_minLength]); }

    /*
     * 最大値
     */
    this.max_value = function(a_maxValue) {
    	if (this === undefined || this === '') return true;
        if (!l_this.decimal.apply(this)) {
            return false;
        } else {
            return parseFloat(this) <= parseFloat(a_maxValue);
        }
    }
    this.maxvalue = function(a_maxValue) { return l_this.max_value.apply(this,[a_maxValue]); }

    /*
     * 最小値
     */
    this.min_value = function(a_minValue) {
    	if (this === undefined || this === '') return true;
        if (!l_this.decimal.apply(this)) {
            return false;
        } else {
            return parseFloat(this) >= parseFloat(a_minValue);
        }
    }
    this.minvalue = function(a_minValue) { return l_this.min_value.apply(this,[a_minValue]); }

    /*
     * 最大値（境界含まず）
     */
    this.max_value_exclusive = function(a_maxValue) {
    	if (this === undefined || this === '') return true;
        if (!l_this.decimal.apply(this)) {
            return false;
        } else {
            return parseFloat(this) < parseFloat(a_maxValue);
        }
    }
    this.maxvalueexclusive = function(a_maxValue) { return l_this.max_value_exclusive.apply(this,[a_maxValue]); }

    /*
     * 最小値（境界含まず）
     */
    this.min_value_exclusive = function(a_minValue) {
    	if (this === undefined || this === '') return true;
        if (!l_this.decimal.apply(this)) {
            return false;
        } else {
            return parseFloat(this) > parseFloat(a_minValue);
        }
    }
    this.minvalueexclusive = function(a_minValue) { return l_this.min_value_exclusive.apply(this,[a_minValue]); }

    /*
     * 正規表現マッチ
     */
    this.pattern = function(a_pattern) {
        if (!gw.isString(this)) {
            return false;
        } else {
            return this.search(a_pattern);
        }
    };

    /*
     * 数字のみ
     */
    this.numeral = function() {
        return l_this.pattern.apply(this, [/^[+-]?[0-9]/]) >= 0;
    }

    /*
     * 整数のみ
     */
    this.integer = function() {
        return l_this.pattern.apply(''+this, [/^[+-]?[0-9]+$/]) >= 0;
    }

    /*
     * 小数点含む
     */
    this.real = function() {
        return l_this.pattern.apply(this, [/^[+-]?[0-9]+\.[0-9]+$/]) >= 0;
    }

    /*
     * 半角英字のみ
     */
    this.alpha = function() {
        return l_this.pattern.apply(this, [/^[a-zA-z¥s]+$/]) >= 0;
    }

    /*
     * 半角英字記号のみ
     */
    this.alsym = function() {
        return l_this.pattern.apply(this, [/^[a-zA-Z\!-\/\:-\@\[-\_\{-\~]+$/]) >= 0;
    }

    /*
     * 半角英数字のみ
     */
    this.alnum = function() {
        return l_this.pattern.apply(this, [/^[a-zA-Z0-9]+$/]) >= 0;
    }
    /*
     * 半角英数字記号のみ
     */
    this.alnumsym = function() {
        return l_this.pattern.apply(this, [/^[a-zA-Z0-9\!-\/\:-\@\[-\_\{-\~]+$/]) >= 0;
    }

    /*
     * メールアドレス
     */
    this.mail = function() {
    	var l_regex = [/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/];
    	return ! l_this.pattern.apply(this, l_regex);
    }
    this.mailformat = function() { return l_this.mail.apply(this); }

}]);
