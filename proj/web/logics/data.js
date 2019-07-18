/*
 * "data.js" presents the data-accessing features
 */
angular.module("nispApp").service("DataService", ["ModelService", "NetworkService", "groundwork", function(model, network, gw) {
	"use strict";

	var l_this = this;

	// {#503} その他のエントリーポイントを整理


	/**
	 * データ検査（バリデーション）
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 検査するデータ領域
	 */
	this.lg_check_data = function(a_context, a_apiData) {
		if ( ! isValid(a_apiData.__model, a_apiData) ) {
			return false;
		}
		return true;
	};

	var entriesList = {};
	function registerRecIds(a_context,a_apiData,a_apiQueryArg) {
		if (! a_apiData.entries) return;

		var entries = { recIds: [], query: a_apiQueryArg, apiData: a_apiData, controllerName: a_context.app.controllerName };
		for (var i in a_apiData.entries) {
			var recid = a_apiData.entries[i].recid;
			entries.recIds.push(recid);
		}
		entriesList[a_context.app.controllerName+"/"+a_apiData.__name] = entries;
	}
	function getEntry(a_context, a_apiData, a_name, a_dir, a_after, a_api_get,a_api_query) {
		a_context.isGetEntry = true;
		var entries = entriesList[a_name];
		var qc = entries.query.queryContext;
		var apiData = entries.apiData;
		var l_after = qc.after;
		var index = entries.recIds.indexOf(a_apiData.recid) + a_dir;
		if (index >= 0 && index < entries.recIds.length) {
			var recid = entries.recIds[index];

			if ( a_api_get != undefined ) {
				a_context.app.controllerURI = a_api_get;
			}
			return l_this.lg_get(a_context, a_apiData, recid, a_after);
		}
		var page = qc.page + a_dir;
		var max = Math.ceil(qc.count/qc.lines);
		if (page > 0 && page <= max) {
			var cur = a_context.app.controllerName;
			a_context.app.controllerName = entries.controllerName;

			if ( a_api_query != undefined ) {
				a_context.app.controllerURI = a_api_query;
			}
			l_this.lg_query(a_context, apiData, entries.query, page, function(a_result,a_data) {
				if ( a_api_query != undefined ) {
					a_context.app.controllerURI = undefined;
				}
				l_after(a_result,a_data,false);
				if (! a_data.entries) return;

				var idx = ( a_dir > 0) ? 0 : a_data.entries.length - 1;
				var recid = a_data.entries[idx].recid;
				a_context.app.controllerName = cur;

				if ( a_api_get != undefined ) {
					a_context.app.controllerURI = a_api_get;
				}
				l_this.lg_get(a_context, a_apiData, recid, a_after);
			});
		}
		return null;
	}

	this.lg_nextEntry = function(a_context, a_apiData, a_nameAfter, a_api_get,a_api_query) {
		getEntry(a_context, a_apiData, a_nameAfter[0], 1, a_nameAfter[1], a_api_get,a_api_query);
		a_context.app.controllerURI = undefined ;
	}

	this.lg_prevEntry = function(a_context, a_apiData, a_nameAfter, a_api_get,a_api_query) {
		getEntry(a_context, a_apiData, a_nameAfter[0], -1, a_nameAfter[1], a_api_get,a_api_query);
		a_context.app.controllerURI = undefined ;
	}

	/**
	 * データ検索
	 *
	 * a_context ... 'app'のコンテキスト。ページングやソートなどのデータ領域
	 * a_apiData ... 検索結果を保持するデータ領域
	 * a_apiQueryArg ... 検索条件のデータ領域
	 * a_page ... 取得ページ（省略すると前回のまま）
	 * a_after ... データ取得後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_query = function(a_context, a_apiData, a_apiQueryArg, a_page, a_after) {
		network.fn_initError(a_apiData);
		try {
		   	if ( !a_after ) {
		   		a_after = createConverter(null,a_context);
		   	} else if (gw.isString(a_after)) {
		   		a_after = createConverter(a_after, a_context,a_apiQueryArg);
		   	}
	   		a_apiQueryArg.queryContext.after = a_after;
			if ( a_apiData.entries ) a_apiData.entries.length = 0;
			if ( a_page !== undefined ) {
				if (String(a_page).indexOf('.') >= 0) {
					a_page = gw.parseInt(a_page) + 1;
				}
				a_apiQueryArg.queryContext.page = a_page;
			}
			var l_apiQueryArg = gw.clone(a_apiQueryArg);
			l_apiQueryArg.queryContext = a_apiQueryArg.queryContext;
			if (l_apiQueryArg.queryContext.logic != 'history' && ! convertOutbound(a_context, l_apiQueryArg, convertOutboundForQuery,undefined,true) ) {
				a_context.app.fn_errorSet(l_apiQueryArg);
				return false;
			}
			network.nl_query(a_context, a_apiData, l_apiQueryArg, a_after);
		} catch (exception) {
			setError(l_apiQueryArg, exception);
			return false;
		}
		return true;
	};

	/**
	 * データ参照
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 参照するデータ領域
	 * a_apiDataKey ... 参照するデータのキー（単一or配列）
	 * a_after ... データ取得後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_get = function(a_context, a_apiData, a_apiDataKeys, a_after) {
		network.fn_initError(a_apiData);

		try {
		   	if ( !a_after ) {
		   		a_after = createConverter(null,a_context);
		   	} else if (gw.isString(a_after)) {
		   		a_after = createConverter(a_after,a_context);
		   	}
			var l_result = network.nl_get(a_context, a_apiData, a_apiDataKeys, a_after);
			if (gw.isSuccess(a_apiData)) {
				a_context.app.setQuery(a_apiData, gw.getPrimaryKey(a_apiData));
				return a_apiData;
			} else {
				return false;
			}
		} catch (exception) {
			setError(a_apiData, exception);
			return false;
		}
	};
	
	this.lg_get2 = function(a_context, a_url, a_after) {
		try {
			return network.nl_get2(a_context, a_url, a_after);
		} catch (exception) {
			return "";
		}
	}


	/**
	 * 更新準備のためにアクセスIDを取得する
	 */
	this.lg_prepare = function(a_context, a_apiData, a_after) {
		var l_result = submit(network.nl_prepare, a_context, a_apiData, a_after, "all");
		if ( l_result  ) {
	   		var l_primaryKey = gw.getPrimaryKey(a_apiData);
	   		a_context.app.setQuery(a_apiData, l_primaryKey);
		}
		return l_result;
	}

	/**
	 * データ更新(主キーが無ければ新規登録)
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 更新対象データのデータ領域
	 * a_after ... 更新後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_submit = function(a_context, a_apiData, a_after) {
		return submit(network.nl_submit, a_context, a_apiData, a_after);
	};

	/**
	 * データ確定
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 確定対象データのデータ領域
	 * a_after ... 送信後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_commit = function(a_context, a_apiData, a_after) {
		return submit(network.nl_commit, a_context, a_apiData, a_after);
	};

	/**
	 * データ削除
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 削除対象データのデータ領域
	 */
	this.lg_remove = function(a_context, a_apiData, a_after, a_commit) {
		a_apiData[__deleted] = true;
		var l_method = a_commit ? network.nl_prepare_then_commit : network.nl_prepare_then_submit;
		return submit(l_method, a_context, a_apiData, a_after);
	};

	// 削除済みのフラグ
	var __deleted = "__deleted";


	/**
	 * データの即時送信
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 対象データのデータ領域
	 */
	this.lg_prepareThenSubmit = function(a_context, a_apiData, a_after) {
		return submit(network.nl_prepare_then_submit, a_context, a_apiData, a_after, "pk");
	};

	/**
	 * データの即時保存
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 対象データのデータ領域
	 */
	this.lg_prepareThenCommit = function(a_context, a_apiData, a_after) {
		return submit(network.nl_prepare_then_commit, a_context, a_apiData, a_after, "pk");
	};

	/**
	 * データの即時保存
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 対象データのデータ領域
	 */
	this.lg_prepareThenUpdate = function(a_context, a_apiData, a_after) {
		return submit(network.nl_prepare_then_update, a_context, a_apiData, a_after, "pk");
	};

	/**
	 * データ更新の確定
	 * 確定の前後でデータ領域が変わるので、両方を渡す。
	 *
	 * a_context ... 'app'のコンテキスト
	 * a_apiData ... 確定対象データのデータ領域(ドラフト領域)
	 * a_apiDataConfirm ... 確定後のデータ領域(本番領域)
	 */
	this.lg_confirmInput = function(a_context, a_apiData, a_apiDataConfirm) {
		gw.copy(a_apiData,a_apiDataConfirm);
		var l_apiData = a_apiData;

		return submit(network.nl_submit, a_context, a_apiDataConfirm, function(a_result,a_apiData) {
			convertInbound(a_context,a_result,a_apiData);

			// ここでのa_apiDataはa_apiDataConfirmなので、l_apiDataにもコピーする
			gw.copy(a_apiData,l_apiData);
		});
	};

	/**
	 * 受信時の変換
	 */
	this.lg_convertInbound = function(a_context, a_result, a_apiData, a_ignorePk) {
		convertInbound(a_context,a_result, a_apiData, a_ignorePk);
	}

	/**
	 * ViewModelが保持しているエラー情報を破棄する
	 */
	this.lg_initError = function(a_apiData) {
		network.fn_initError(a_apiData);
	}

	/**
	 * 履歴一覧取得（検索条件なし）
	 * a_context ... 'app'のコンテキスト。ページングやソートなどのデータ領域
	 * a_apiData ... 検索結果を保持するデータ領域
	 * a_recId ... レコードID
	 * a_lines ... 1ページの行数
	 * a_page ... 取得ページ（省略すると前回のまま）
	 * a_after ... データ取得後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_queryHistories = function(a_context, a_apiData, a_recId, a_lines, a_page, a_after) {
		var l_apiQueryArg = {
			__model: a_apiData.__model,
			recid: a_recId,
			queryContext: {
				logic: "history",
				lines: a_lines,
			}
		};
		return l_this.lg_query(a_context, a_apiData, l_apiQueryArg, a_page, a_after);
	}

	/**
	 * 履歴から一件取得する
	 * a_context ... 'app'のコンテキスト。ページングやソートなどのデータ領域
	 * a_apiData ... 検索結果を保持するデータ領域
	 * a_recId ... レコードID
	 * a_opeId ... 操作ID
	 * a_after ... データ取得後に呼ぶ処理（省略するとconvertInbound）
	 */
	this.lg_getHistory = function(a_context, a_apiData, a_recId, a_opeId, a_after) {
		a_apiData.queryContext.logic = "history";
		var l_result = l_this.lg_get(a_context, a_apiData, [a_recId, a_opeId], a_after);
		delete a_apiData.queryContext.logic;
		return l_result;
	}

	//// UTILS. ////

	function createConverter(a_after,a_context,a_apiQueryArg) {
		return function(a_result,a_data,a_execDone,a_resultFlag) {
			convertInbound(a_context,a_result, a_data, false, a_apiQueryArg);
			if (a_execDone && a_after) {
				return a_context.app.fn_call("lg_do", a_after, a_data, a_resultFlag);
			}
			return false;
		};
	}

	function submit(a_method, a_context, a_apiData, a_after, a_ignore) {
		a_apiData.entries = undefined;
		var l_apiDataName = a_apiData.__name;

		var l_check = convertOutbound(a_context,a_apiData, convertOutboundForSubmit, a_ignore);
		if ( ! l_check ) {
			a_context.app.fn_errorSet(a_apiData);
			convertInbound(a_context,a_apiData,a_apiData,a_ignore);
			return false;
		} else {
			network.fn_initError(a_apiData);
		}

	   	if ( !a_after ) {
	   		a_after = createConverter(null,a_context);
	   	} else if (gw.isString(a_after)) {
	   		a_after = createConverter(a_after,a_context);
	   	}

		try {
			var l_result = a_method(a_context, l_apiDataName, a_apiData, a_after);
			if ( a_context.app.fn_errorSet(a_apiData) || !gw.isSuccess(a_apiData) ) {
				convertInbound(a_context,a_apiData,a_apiData,a_ignore);
				return false;
			}
			return l_result;
		} catch (exception) {
			setError(a_apiData, exception);
			convertInbound(a_context,a_apiData,a_apiData,a_ignore);
			return false;
		}
	}
	/**
	 * APIサーバ向けに変換
	 */
	function convertOutbound(a_context, a_apiData, a_convertOutboundInternal, a_ignore, a_fromQuery) {
		network.fn_initError(a_apiData);
//		if (a_apiData.isConverted) return true;
		
		a_apiData.isConverted = true;
		if ( a_convertOutboundInternal ) {
			a_convertOutboundInternal(a_apiData, false);
			if ( ! isValid(a_apiData.__model, a_apiData, undefined, a_ignore) ) {
				return false;
			}
			a_convertOutboundInternal(a_apiData, true);
		}
		convertUndefined(a_apiData, (a_fromQuery==true?false:true) );
		return true;
	};

	this.lg_convertOutboundForQuery = function(a_context,a_apiData) {
		return convertOutbound(a_context,a_apiData,convertOutboundForQuery,undefined,true);
	}

	/**
	 * 表示向けに変換
	 */
	function convertInbound(a_context,a_result,a_apiData, a_ignorePk, a_apiQueryArg) {
		if ( gw.isArray(a_result.entries) ) {
			a_apiData.entries = [];
		}
		var l_context = a_apiData.queryContext;
		gw.copy(a_result,a_apiData, false);
		var l_primaryKey = gw.getPrimaryKeyName(a_apiData);
		var l_pk = a_result[l_primaryKey];
		if (l_pk) {
			a_apiData[l_primaryKey] = l_pk;
		}
		if ( a_apiData.entries ) {
			for (var i=0; i<a_apiData.entries.length; i++) {
				var l_apiData = a_apiData.entries[i];
				l_apiData.__model = a_apiData.__model;
				l_apiData.__name = a_apiData.__name;
				convertDataTypeAll(l_apiData);
				model.lg_visualize(a_apiData.__model, l_apiData);
			}
		}
		convertDataTypeAll(a_apiData);
	   	model.lg_visualize(a_apiData.__model, a_apiData);

	   	l_context.count = a_result.count;
		a_apiData.queryContext = l_context;
		if (! a_apiQueryArg)
			a_apiQueryArg = a_context.app.getQuery(a_apiData);
		registerRecIds(a_context,a_apiData,a_apiQueryArg);
	}

	/**
	 * データの検証をする
	 */
	function isValid(a_model, a_apiData, a_idx, a_ignore) {
		network.fn_initError(a_apiData);
		if ( a_ignore == "all" ) return true;

		if (!a_model) {
			gw.arrayAppend(a_apiData.queryContext.errors, [{name:"ERROR",message: "Model is missing."}]);
			return false;
		}
		function inner(a_model, a_apiData, a_idx, a_ignore) {
			var errors = [];
			try {
				errors = model.lg_validate(a_model, a_apiData, a_ignore);
			} catch (exception) {
				var msg = exception.message;
				errors = gw.arrayAppend(errors,[{name:"ERROR",message:msg}]);
			}
			if ( errors.length > 0 ) {
				if ( a_idx !== undefined ) {
					for ( var i = 0; i < errors.length; i++ ) {
						errors[i].idx = a_idx;
					}
				}
				a_apiData.queryContext.errors = gw.arrayAppend(a_apiData.queryContext.errors,errors);
				return true;
			}
			return false;
		}
		var error = false;
		if (a_model.multiple && a_apiData.entries ) {
			for ( var i = 0; i < a_apiData.entries.length; i++ ) {
				error |= inner(a_model, a_apiData.entries[i], a_idx, a_ignore);
			}
		}
		if ( !a_model.multiple ) {
			error |= inner(a_model, a_apiData, undefined, a_ignore);
		}
		return ! error;
	}

	function convertDataTypeAll(a_apiData) {
		var l_model = a_apiData.__model;
		function inner(a_apiData, a_model, a_key) {
			var l_subItems = gw.getSubItems(a_model);
			for ( var l_key in a_apiData ) {
				if ( gw.startsWith(l_key,"__") ) continue;

				var l_item = a_apiData[l_key];
				if (!gw.isArray(l_item) && gw.isObject(l_item)) {
					var l_subModel = l_subItems[l_key];
					if (l_subModel) {
						inner(l_item, l_subModel, l_key);
					}
				} else {
					convertDataType(a_apiData,l_subItems,l_key,l_item);
				}
			}
		}
		inner(a_apiData,l_model);
	}
	function convertDataType(a_apiData,a_subItems,a_key,a_item, a_convStr) {
		if (a_item === null) {
			a_apiData[a_key] = undefined ;
			return;
		}
		if (a_item === undefined) return;
		if (!a_subItems) return;
		if (gw.isArray(a_item)) return;

		if (gw.isAncestor(a_subItems[a_key], gw.app.models.integer)) {
			a_apiData[a_key] = a_convStr ? (a_item + "") : parseInt(a_item + "",10);
		}
	}

	/**
	 * Submit送信用に変換する。
	 * 'value'という名の下位項目の値でその項目を置き換える。
	 */
	function convertOutboundForSubmit(a_apiData,a_doValue) {
		var l_subItems = gw.getSubItems(a_apiData);
		function inner(a_apiData, a_key, a_parent) {
			for ( var l_key in a_apiData ) {
				if ( gw.startsWith(l_key,"__") ) continue;
				if ( l_key == "queryContext" ) continue;

				var l_item = a_apiData[l_key];
				if ( a_doValue && l_key == "value" ) {
					if ( a_parent && l_item != "----" ) {
						// 親項目を'value'の値で置き換える
		   				a_parent[a_key] = l_item;
					}
				} else if ( gw.isObject(l_item) ) {
					// 下位項目を持つオブジェクトを再帰処理
					inner(l_item,l_key,a_apiData);
				} else {
					convertDataType(a_apiData,l_subItems,l_key,l_item, false);
				}
				if (! l_subItems) continue;

				var l_subItem = l_subItems[l_key];
				if (l_subItem && l_subItem.read_only) {
					delete a_apiData[l_key];
				}
			}
		}
		inner(a_apiData);
	}

	/**
	 * Query送信用に変換する。
	 * 'value'という名の下位項目の値でその項目を置き換える。
	 * 定義に基づき比較演算子を付加する。
	 */
	function convertOutboundForQuery(a_apiData, a_doValue) {
		var l_subItems = gw.getSubItems(a_apiData);
	 	function inner(a_apiData, a_key, a_parent) {
			for ( var l_key in a_apiData ) {
				if ( gw.startsWith(l_key,"__") ) continue;
				if ( l_key == "queryContext" ) continue;

				var l_item = a_apiData[l_key];
				if (l_key == "nengo" && l_item == "--") {
					a_parent[a_key] = undefined;
					continue;
				}
				if ( a_doValue && l_key == "value" ) {
					if ( a_parent && l_item != "----" ) {
						// 親項目を'value'の値で置き換える
		   				a_parent[a_key] = l_item;
					}
				} else if ( gw.isObject(l_item) ) {
					// 下位項目を持つオブジェクトを再帰処理
					inner(l_item,l_key,a_apiData);
				} else {
					convertDataType(a_apiData,l_subItems,l_key,l_item, true);
				}
			}
			convertUndefined(a_apiData,false);
		}
		inner(a_apiData);
	}
	/**
	 * 空値を処理する
	 */
	function convertUndefined(a_apiData, a_convertEmptyFlg) {
		// TODO sub_itemsにあるフィールド全てをチェックし、空値として処理する
		for ( var l_key in a_apiData ) {
			var l_item = a_apiData[l_key];
			if (gw.isFunction(l_item)) continue;
			
			if ( a_convertEmptyFlg && l_item === l_this.EmptyValue ) {
				a_apiData[l_key] = "";
			//} else if ( l_item === '' ) {
			} else if ( a_convertEmptyFlg && l_item === undefined ) {
				a_apiData[l_key] = "";
			} else if ( ! a_convertEmptyFlg && l_item === '' ) {
				delete a_apiData[l_key];
			}
		}
	}
	this.EmptyValue = 'jp.co.nisp.api.EmptyValue';

	function setError(a_apiData, a_exception) {
		gw.app.fn_setError(a_apiData, "ERROR", a_exception);
	}

}]);
