/*
 * "logic.js"
 */
angular.module("nispApp").service("LogicService", ["ContentsService", "DataService", "groundwork", function(contents, data, gw) {
    "use strict";

    var l_this = this;
    this.data = data;

    var l_currentPath = undefined;

    this.setCurrentPath = function(a_func) {
    	l_currentPath = a_func;
    }

    // ロジック successに処理成功時、failに失敗時の遷移先の画面名を記載する
    this.logics = {
    	// 画面切替系ロジック
        lg_get_contents: {
            service: "contents",
            //name: undefined  // 未指定の場合はキーに同じ
        },
        lg_get_service_contents: {
            service: "contents"
        },

        // データ操作系ロジック
        lg_check_data: {
        	service: "data"
        },
        lg_query: {
            service: "data"
        },
        lg_get: {
            service: "data"
        },
        lg_get2: {
            service: "data"
        },
        lg_prepare: {
            service: "data", success: ["edit"]
        },
        lg_submit: {
            service: "data", success: ["confirm"]
        },
        lg_commit: {
            service: "data", success: ["complete"]
        },
        lg_remove: {
            service: "data", success: ["complete"]
        },
        lg_prepareThenSubmit: {
        	service: "data", success: ["confirm"]
        },
        lg_prepareThenCommit: {
        	service: "data", success: ["complete"]
        },
        lg_prepareThenUpdate: {
        	service: "data", success: ["complete"]
        },
        lg_prepareThenCommitNotGo: {
        	name: "lg_prepareThenCommit",
        	service: "data",
        },
        lg_confirmInput: {
            service: "data"
        },
        lg_convertInbound: {
        	service: "data"
        },
        lg_initError: {
        	service: "data"
        },
        lg_queryHistories : {
        	service: "data"
        },
        lg_getHistory : {
        	service: "data"
        },
        lg_nextEntry : {
        	service: "data"
        },
        lg_prevEntry : {
        	service: "data"
        },

        //ローカル系ロジック
        lg_do: {
            service: "local"
        },
        lg_closeWindow: {
        	service: "local"
        },
        lg_openDialog: {
        	service: "local"
        },
        lg_closeDialog: {
        	service: "local"
        },
        lg_keydown: {
        	service: "local"
        },
        lg_goto: {
        	service: "local"
        },
        lg_next: {
        	service: "local"
        },
        lg_prev: {
        	service: "local"
        },
        lg_clearQueryCond : {
        	service: "local"
        },
        lg_nextData: {
        	service: "local"
        },
        lg_prevData: {
        	service: "local"
        },
        lg_tabnext: {
        	service: "local"
        },
    };

    // ローカルロジック
    this.local_logics = {
    	lg_do : function(a_logicName) {
        	var l_args = Array.apply(null, arguments);
        	l_args.shift();
        	var func = l_this.local_logics[a_logicName];
        	if (func) return func.apply(null, l_args);
        	return false;
    	},
    	lg_closeWindow: function() {
    		var l_cookie = gw.app.context.settings.cookie;
    		gw.setCookie(l_cookie.access_id_key, gw.app.context.session.access_id, l_cookie.max_age);
    		window.close();
    	},
    	lg_openDialog: function(a_dialogId, a_title) {
    		gw.app.fn_openDialog(a_dialogId, a_title);
    	},
    	lg_closeDialog: function(a_dialogId) {
    		gw.app.fn_closeDialog(a_dialogId);
    	},
    	lg_keydown: function(a_event) {
    		if ( !a_event || !a_event.keyCode ) return false;
    		switch (a_event.keyCode) {
    		case 113: // F2 TOPページへ
    			gw.app.fn_topPage();
    			return keyCancel(a_event);
    		/*
    		case 8:  // BackSpace
    			var elm = a_event.path[0];
    			if (elm.tagName.toLowerCase() == 'textarea' || elm.tagName.toLowerCase() == 'input' && elm.type.toLowerCase() == 'text') break;
    			return keyCancel(a_event);
    		case 37: if (! a_event.altKey) break;  // ALT+←
				return keyCancel(a_event);
    		case 82: if (! a_event.ctrlKey) break; // CTRL+R
    			return keyCancel(a_event);

    		case 112: // F1 OK
    			gw.app.fn_ok();
    			return keyCancel(a_event);
    		case 113: // F2 NO
    			gw.app.fn_no();
    			return keyCancel(a_event);
    		case 114: // F3 キャンセル
    			gw.app.fn_cancel();
    			return keyCancel(a_event);
    		case 115: // F4 戻る
    			gw.app.fn_return();
    			return keyCancel(a_event);
    		case 123: // F12 TOPページへ
    			gw.app.fn_topPage();
    			return keyCancel(a_event);

    		case 116: // F5 一覧：先頭へ
    			gw.app.fn_goListTop();
    			return keyCancel(a_event);
    		case 117: // F6 一覧：前へ
    			gw.app.fn_goListPrev();
    			return keyCancel(a_event);
    		case 118: // F7 一覧：次へ
    			gw.app.fn_goListNext();
    			return keyCancel(a_event);
    		case 119: // F8 一覧：最後へ
    			gw.app.fn_goListLast();
    			return keyCancel(a_event);
    		*/
    		}
    		return true;
    	},
    	// 数字：そのインデックスへ。文字列：そのパスのViewへ。配列：現在のViewにそのパーツを当てはめる
        lg_goto: contents.lg_goto,
        lg_next: contents.lg_next,
        lg_prev: contents.lg_prev,
        lg_tabnext: contents.lg_tabnext,

        lg_clearQueryCond: function(a_query) {
        	gw.app.fn_clearCond(a_query);
        },
        lg_nextData: function(a_detail, a_index) {
        	gw.app.fn_nextData(a_detail, a_index);
        },
        lg_prevData: function(a_detail, a_index) {
        	gw.app.fn_prevData(a_detail, a_index);
        },
   };
    function keyCancel(a_event) {
		if (a_event.preventDefault) {
			// デフォルトの動作を無効化する
			a_event.preventDefault();
		} else {
			// デフォルトの動作を無効化する（非標準）
			a_event.keyCode = 0;
		}
		return false;
    }

    /*
     * ローカルロジック定義
     */
    this.lg_local_logics = function(a_logics, a_dataPlace) {
        importLogics(a_logics, a_dataPlace);
    };

    function importLogics(a_logics, a_dataPlace) {
        for (var l_logicName in a_logics) {
            l_this.local_logics[l_logicName] = new function(a_logics, a_logicName, a_dataPlace) {
                return function(a_args) {
                	var l_func = a_logics[a_logicName];
                	var l_args = [a_dataPlace].concat(a_args);
                    return l_func.apply(a_dataPlace, l_args);
                };
            } (a_logics, l_logicName, a_dataPlace);
        }
    }


    // {#501}単にLogicのエントリーポイントを列挙するだけでなく、ある種のワークフローエンジンを提供する


    /*
     * 各種ロジック呼び出し
     */
    this.lg_call = function(a_context, a_callArgs) {
        var l_logicCallDef = logicCallDef(a_callArgs);
        if (!gw.exists(l_logicCallDef)) {
            throw new gw.LogicIntegrityCollapsedException();
        }
        var l_name = "lg_" + l_logicCallDef.service + "_logic_call";
        var l_func = l_this[l_name];
        var l_args = [l_logicCallDef, [a_context].concat(l_logicCallDef.args)];
        return l_func.apply(l_this, l_args);
    }

    function logicCallDef(a_callArgs) {
        var l_logicName = a_callArgs.shift();
        var l_logicArgs = a_callArgs;
        var l_logicCallDef = l_this.logics[l_logicName];
        if (!gw.exists(l_logicCallDef)) {
            return undefined;
        }
        l_logicCallDef = gw.clone(l_logicCallDef);
        if (!gw.exists(l_logicCallDef.name)) {
            l_logicCallDef.name = l_logicName;
        }
        l_logicCallDef.args = l_logicArgs;
        return l_logicCallDef;
    }

    /*
     * コンテンツロジック呼び出し
     */
    this.lg_contents_logic_call = function(a_def, a_logicArgs) {
    	var l_contents = contents[a_def.name].apply(contents, a_logicArgs);
        return {contents: l_contents};
    }

    /*
     * データロジック呼び出し
     */
    this.lg_data_logic_call = function(a_def, a_logicArgs) {
    	var l_func = data[a_def.name];
    	if (l_func) {
            var l_result = l_func.apply(data, a_logicArgs);
            return returnLogic(l_result, a_def);
    	} else {
    		return false;
    	}
    }

    /*
     * ローカルロジック呼び出し
     */
    this.lg_local_logic_call = function(a_def, a_logicArgs) {
    	var l_context = a_logicArgs.shift();
        var l_localLogicName = a_def.name;
        var l_result = l_this.local_logics[l_localLogicName].apply(null,a_logicArgs);
        return returnLogic(l_result, a_def);
    }

    function returnLogic(a_result,a_def) {
        var l_nextContents = a_result ? a_def.success : a_def.fail;  // {#501} 画面遷移制御、nextContentsを決定する
        if (l_nextContents && !contents.sequence ) {
        	var l_contents = [];
        	for ( var i=0; i<l_nextContents.length; i++ ) {
        		l_contents.push(l_currentPath+'/'+l_nextContents[i]);
        	}
            return {result:a_result, contents:l_contents};
        }
        return {result:a_result};
    }

}]);
