/*
 * "model.js"
 */
angular.module("nispApp").service("ModelService", ["groundwork", function(gw) {
    "use strict";

    var l_this = this;

    // ノーマライゼーションロジック
    this.normalizers = [];

    // ビジュアライゼーションロジック
    this.visualizers = [];

    // バリデーションロジック
    this.validators = {};

    // モデル定義
    this.models = {};

    /**
     * ノーマライゼーションロジック
     */
    this.lg_normalizers = function(a_normalizers) {
    	importNormalizers(a_normalizers);
    };
    function importNormalizers(a_normalizers) {
    	for (var l_name in a_normalizers) {
    		l_this.normalizers[l_name] = a_normalizers[l_name];
    	}
    }
    /**
     * ビジュアライゼーションロジック
     */
    this.lg_visualizers = function(a_visualizers) {
    	importVisualizers(a_visualizers);
    };
    function importVisualizers(a_visualizers) {
    	for (var l_name in a_visualizers) {
    		l_this.visualizers[l_name] = a_visualizers[l_name];
    	}
    }

    /**
     * バリデーションロジック
     */
    this.lg_validators = function(a_validators) {
        importValidators(a_validators);
    };

    function importValidators(a_validators) {
        for (var l_validatorName in a_validators) {
            l_this.validators[l_validatorName] = a_validators[l_validatorName];
        }
    }

    /**
     * モデル定義
     */
    this.lg_models = function(a_models, a_dataPlace) {
        importModels(a_models);
        resolveModels(a_models);
        if (a_dataPlace) {
            createData(a_dataPlace, a_models);
        }
    };

    function importModels(a_models) {
        for (var l_modelName in a_models) {
        	var l_model = a_models[l_modelName];
        	l_this.models[l_modelName] = l_model;
        }
    }

    function resolveModels(a_models, a_parent) {
        for (var l_modelName in a_models) {
        	var l_model = a_models[l_modelName];
        	if (l_model instanceof String)
        		continue;
        	if ( a_parent ) l_model.parent = a_parent;
            resolveBaseModel(l_model, (a_parent === undefined));
            resolveModels(l_model.sub_items, l_model);
            resolveValidators(l_model);
        	resolveNormalizer(l_model,"normalizations",normalizer);
        	resolveNormalizer(l_model,"visualizations",visualizer);
        }
    }

    function resolveBaseModel(a_model, a_isBase) {
        var l_baseModelName = a_model.base_model;
        if (gw.exists(l_baseModelName) && gw.isString(l_baseModelName)) {
            if (l_baseModelName) {
            	if (l_this.models[l_baseModelName]) {
                    a_model.base_model = l_this.models[l_baseModelName];
                    inner(a_model, a_model.base_model);
            	} else {
                    // TODO base_modelが見つからないケース
            		delete a_model.base_model;
            	}
            }
        }
        function inner(a_model, a_base) {
            for (var l_name in a_base) {
            	if (l_name === "sub_items") continue;
            	if (l_name === "queryContext") continue;
            	if (l_name === "entries") continue;
            	if (l_name === "base_model") continue;
            	if (gw.startsWith(l_name,"__")) continue;
            	if (a_isBase && l_name === "name") continue;

            	var l_org = a_base[l_name];
            	var l_item = a_model[l_name];
            	if (gw.isObject(l_org)) {
            		if (l_item === undefined) {
            			l_org = gw.clone(l_org);
                		a_model[l_name] = l_org;
            		} else {
                		inner(l_item,l_org);
            		}
            	} else if (l_item === undefined) {
            		a_model[l_name] = l_org;
            	}
            }
            if (a_base.sub_items === undefined) return;

            if (! a_model.sub_items) a_model.sub_items = {};
            for (var l_name in a_base.sub_items) {
            	var l_org = a_base.sub_items[l_name];
            	var l_item = a_model.sub_items[l_name];
            	if (l_org && l_item === undefined) {
            		a_model.sub_items[l_name] = gw.clone(l_org);
            	}
            }
        }
    }

    //検索条件以外のパラメータ
    this.queryContext = {
    	result: true,	//falseだと検索結果総数のみを返す
    	lines: 10,	//1ページ分の行数
    	page: 1,	//取得ページ
    	sortKey: "recid",
    	sortDir: "!",
    	// ここ以下はサーバ送信時に削除され、受信時にセットされる
    	count: 0,	//検索結果総数
        errors: [],	//エラー情報
    };
    this.fn_initContext = function(a_dataPlace, a_models) {
        for (var l_modelName in a_models) {
        	var l_context = gw.clone(l_this.queryContext);
            a_dataPlace[l_modelName].queryContext = l_context;
        }
    }

    function createData(a_dataPlace, a_models) {
        for (var l_modelName in a_models) {
        	var l_model = a_models[l_modelName];
        	var l_data = {
                __name: gw.exists(l_model.name) ? l_model.name : l_modelName,
                __model: l_model,
                entries: l_model.multiple ? [] : undefined  // TODO
            };
            a_dataPlace[l_modelName] = l_data;
            l_model.realData = l_data;
            if (l_model.base_model) l_model.base_model.realData = l_data;
/*
    		//アクセサの設定
        	for (var l_accName in l_collectionAccessor) {
        		l_data[l_accName] = l_collectionAccessor[l_accName];
        	}
        	for (var l_accName in l_modelAccessor) {
        		l_data[l_accName] = l_modelAccessor[l_accName];
        	}
*/
        }
        l_this.fn_initContext(a_dataPlace, a_models);
    }

    /*
     * ノーマライゼーション
     */

    function resolveNormalizer(a_model,a_normalizations,a_normalizer) {
    	if (gw.exists(a_model[a_normalizations])) {
    		if ( ! gw.isArray(a_model[a_normalizations]) ) {
    			a_model[a_normalizations] = a_normalizer(a_model[a_normalizations]);
    		} else {
    			for ( var l_idx = 0; l_idx < a_model[a_normalizations].length; l_idx++ ) {
    				var l_normalizer = a_model[a_normalizations][l_idx];
    				if ( gw.exists(l_normalizer) ) {
    					a_model[a_normalizations][l_idx] = a_normalizer(l_normalizer);
    				}
    			}
    		}
    	}
    }
    function normalizer(a_normalizerCallArgs) {
        return converter(l_this.normalizers,a_normalizerCallArgs);
    }
    function visualizer(a_normalizerCallArgs) {
        return converter(l_this.visualizers,a_normalizerCallArgs);
    }
    function converter(a_converters, a_args) {
        var l_normalizerName = a_args;
        return new function(a_normalizerName) {
            return function() {
            	if (gw.isString(a_normalizerName)) {
                	var l_func = a_converters[a_normalizerName];
                	if (l_func) {
                        return l_func.apply(this);
                	} else {
                		return false;
                	}
            	} else if (a_normalizerName.apply){
                    return a_normalizerName.apply(this);
            	} else {
            		return false;
            	}
                // TODO 存在しない場合
            };
        } (l_normalizerName);
    }

    /**
     * ノーマライズ
     */
    this.lg_normalize = function(a_dataModel, a_data) {
        return normalizeItem(a_dataModel, a_data, "normalizations");
    }

    function normalizeItem(a_dataModel, a_data, a_callItems) {
    	if ( !a_dataModel ) return a_data;

        if (a_dataModel.sub_items) {
        	a_data = normalizeSubItems(a_dataModel,a_dataModel.sub_items, a_data, a_callItems);
        }
        if (a_dataModel[a_callItems]) {
        	a_data = callNormalizers(a_dataModel,a_dataModel[a_callItems], a_data);
        }
        return a_data;
    }
    function bothObject(a_lhs,a_rhs) {
    	return gw.isObject(a_lhs) == gw.isObject(a_rhs);
    }

    function normalizeSubItems(a_dataModel, a_subItemModels, a_data, a_callItems) {
        for (var l_subItemName in a_subItemModels) {
            var l_subItemModel = a_subItemModels[l_subItemName];
            if (l_subItemName.substring(0, 2) == "__") {
                continue;
            }
            if ( ! gw.isObject(a_data) ) {
            	a_data = {value:a_data};
            }
            var l_subItem = a_data[l_subItemName];
            var l_value = normalizeItem(l_subItemModel, l_subItem, a_callItems);
            a_data[l_subItemName] = l_value;
        }
        return a_data;
    }

    function callNormalizers(a_dataModel,a_normalizer, a_data) {
    	if ( a_normalizer && !gw.isArray(a_normalizer) ) {
    		a_data = a_normalizer.apply(a_data);
    	} else {
            for (var l_key in a_normalizer) {
            	var l_normalizer = a_normalizer[l_key];
            	a_data = l_normalizer.apply(a_data);
            }
    	}
        return a_data;
    }

    /**
     * ビジュアライズ
     */
    this.lg_visualize = function(a_dataModel, a_data) {
        return normalizeItem(a_dataModel, a_data, "visualizations");
    }

    /*
     * バリデーション
     */

    function resolveValidators(a_model) {
        if (gw.exists(a_model.validations)) {
    		if ( ! gw.isObject(a_model.validations) ) {
        		a_model.validations = validator(a_model.validations);
    		} else {
	            for (var l_validationKey in a_model.validations) {
	                var l_validation = a_model.validations[l_validationKey];
	                if (gw.exists(l_validation) && gw.isArray(l_validation)) {
	                	l_validation.push(a_model);
	                    a_model.validations[l_validationKey] = validator(l_validation);
	                }
	            }
    		}
        }
    }
    function validator(a_validatorCallArgs) {
        var l_validatorName = a_validatorCallArgs.shift();
        var l_validatorArgs = a_validatorCallArgs;
        return new function(a_validatorName, a_validatorArgs) {
            return function() {
            	if (gw.isString(a_validatorName) && l_this.validators[a_validatorName]) {
                    return l_this.validators[a_validatorName].apply(this, a_validatorArgs);
            	} else if (a_validatorName && a_validatorName.apply) {
            		return a_validatorName.apply(this);
            	}
                // TODO 存在しない場合
            };
        } (l_validatorName, l_validatorArgs);
    }

    /**
     * バリデート
     */
    this.lg_validate = function(a_dataModel, a_data, a_ignore) {
    	a_data = this.lg_normalize(a_dataModel, a_data);
        var l_result = [];
        var l_path = '';
        validateItem(l_result, a_dataModel, a_data, l_path, a_ignore);
        return l_result;
    }

    function validateItem(a_result, a_dataModel, a_data, a_path, a_ignore, a_isBase) {
    	if ( ! a_dataModel ) return;
    	if ( a_data === undefined && ! a_dataModel.mandatory ) return;

    	if ( !a_isBase ) {
    		if (a_dataModel.__name) {
    			if (a_path) a_path += '.';
        		a_path += a_dataModel.__name;
    		}
    	}

        if (gw.exists(a_dataModel.sub_items)) {
            validateSubItems(a_result, a_dataModel.sub_items, a_data, a_path, a_ignore);
        }
        if (gw.exists(a_dataModel.validations)) {
            callValidators(a_result, a_dataModel.validations, a_data, a_path);
        }
        if (gw.isMandatory(a_dataModel) && !gw.exists(a_data)) {
            a_result.push( { name:a_path, message:'mandatory'} );
        }
    }

    function validateSubItems(a_result, a_subItemModels, a_data, a_path, a_ignore) {
        for (var l_subItemName in a_subItemModels) {
            var l_subItemModel = a_subItemModels[l_subItemName];
        	if ( a_ignore && (l_subItemModel.primary_key || l_subItemName == "recid") ) {
        		continue;
        	}
            if (l_subItemName.substring(0, 2) == "__") {
                continue;
            }
            var l_subItem = a_data[l_subItemName];
            var l_path = a_path ? (a_path+'.'+l_subItemName) : l_subItemName;
            validateItem(a_result, l_subItemModel, l_subItem, l_path);
        }
        // TODO 余計な項目のチェック
        // TODO 必須のチェック
    }

    function callValidators(a_result, a_validations, a_data, a_path) {
        for (var key in a_validations) {
            var l_validator = a_validations[key];
            if (l_validator && !l_validator.apply(a_data)) {
                a_result.push( { name:a_path, message:key} );
            }
        }
    }


    /*
     * データ配列アクセッサー
     */
    var l_entryMap = {};
	function initMap(a_this) {
		var l_map = l_entryMap[a_this.__name];
    	if ( l_map ) return l_map;

    	if (a_this.entries === undefined || a_this.entries === null) a_this.entries = [];
    	l_map = {};
    	l_entryMap[a_this.__name] = l_map;
		if ( gw.exists(a_this.entries) ) {
			var l_keyName = gw.getPrimaryKeyName(a_this.__model);
			for (var i in a_this.entries) {
				var l_entry = a_this.entries[i];
				var l_key = l_entry[l_keyName];
				putMap(a_this,l_key, l_entry);
			}
		}
		return l_map;
	}
	function putMap(a_this,a_key,a_entry) {
		initMap(a_this)[a_key] = a_entry;
	}
	function removeMap(a_this,a_key) {
		var l_map = initMap(a_this);
        delete l_map[a_key];
	}
    var l_collectionAccessor = {
    	/**
    	 * 初期化する
    	 */
    	init: function() {
    		var l_map = initMap(this);
    		for ( var l_key in l_map ) {
    			delete l_map[l_key];
    		}
    		this.entries.length = 0;
    		return this;
    	},
        /**
         * 全件を取得する
         */
        all: function() {
        	initMap(this);
            return this.entries;
        },
    	/**
    	 * キーを指定して取得する
    	 */
        pick: function(a_key) {
    		var l_map = initMap(this);
    		if ( ! Object.keys(l_map).length ) return undefined;
            return l_map[a_key];
        },
        /**
         * 位置を指定して取得する
         */
        at: function(a_index) {
    		var l_map = initMap(this);
    		if (a_index < 0 || a_index >= this.entries.length) return undefined;
            return this.entries[a_index];
        },
        /**
         * 指定した範囲のデータの配列を返す
         */
        range: function(a_startIndex, a_endIndex) {
        	initMap(this);
        	if (a_endIndex >= this.entries.length) {
        		a_endIndex = this.entries.length - 1;
        	}
            var l_entries = [];
        	for (var l_idx = a_startIndex; l_idx <= a_endIndex; l_idx++) {
        		var l_entry = this.entries[l_idx];
       			l_entries.push(l_entry);
        	}
            return l_entries;
        },
        /**
         * 指定した条件にマッチするデータの配列を返す
         */
        subset: function(a_predicate) {
        	initMap(this);
        	var l_entries = [];
        	for (var l_idx = 0; l_idx < this.entries.length; l_idx++) {
        		var l_entry = this.entries[l_idx];
        		if ( a_predicate(l_entry) ) {
        			l_entries.push(l_entry);
        		}
        	}
            return l_entries;
        },
        /**
         * 末尾に追加
         *
         * 同一キーのエントリーが既に存在したらエラー
         */
        add: function(a_entry) {
        	initMap(this);
        	var l_key = gw.getPrimaryKey(a_entry);
            if ( this.pick(l_key) ) return this;
            this.entries.push(a_entry);
            putMap(this, l_key, a_entry);
            return this;
        },
        /**
         * 指定位置に挿入
         *
         * 同一キーのエントリーが既に存在したらエラー
         */
        insert: function(a_index, a_entry) {
        	initMap(this);
        	var l_key = gw.getPrimaryKey(a_entry);
            if ( this.pick(l_key) ) return this;
            this.entries.splice(a_index,0,a_entry);
            putMap(this, l_key, a_entry);
            return this;
        },
        /**
         * 指定位置へ移動
         *
         * 第二引数のキーの差すエントリーを、第一引数の位置へ移動
         */
        move: function(a_index, a_key) {
        	initMap(this);
        	var l_entry = this.pick(a_key);
        	if ( l_entry ) {
        		for (var l_idx = 0; l_idx < this.entries.length; l_idx++) {
        			if (this.entries[l_idx] === l_entry) {
    					this.entries.splice(l_idx,1);
    					this.entries.splice(a_index,0,l_entry);
    					break;
        			}
        		}
        	}
            return this;
        },
        /**
         * 同一キーのエントリーを書き換え
         *
         * 指定された項目のみ書き換える。
         */
        modify: function(a_items) {
        	initMap(this);
            if (gw.isObject(a_items)) {
            	var l_keyName = gw.getPrimaryKeyName(this);
            	var l_key = a_items[l_keyName];
            	var l_entry = this.pick(l_key);
            	l_entry.set(a_items);
            }
            return this;
        },
        /**
         * エントリーの除去
         *
         * 配列から除くだけなので、削除APIは呼ばない
         */
        remove: function(a_key) {
        	initMap(this);
        	var l_entry = this.pick(a_key);
        	if (l_entry === undefined) return;

            for (var l_idx = 0; l_idx < this.entries.length; l_idx++) {
            	if (l_entry === this.entries[l_idx] ) {
            		this.entries.splice(l_idx,1);
            		break;
            	}
            }
            removeMap(this,a_key);
            return this;
        }
    };

     /*
      * 単一データ・アクセッサー
      */
    var l_modelAccessor = {
        get: function(a_itemName) {
            return this[a_itemName];
        },
        set: function(a_arg1, a_arg2) {
            if (gw.exists(a_arg2)) {
                //第二引数があれば第一引数をフィールド名として代入
                this[a_arg1] = a_arg2;
            } else if (gw.isObject(a_arg1) ){
                //なければ第一引数をオブジェクトとみなし、その全フィールドをコピー
            	gw.copy(a_arg1,this);
            } else {
            	//オブジェクトでないとエラー
            	throw new Exception("NOT OBJECT" + a_arg1);
            }
            return this;
        }
    };
}]);
