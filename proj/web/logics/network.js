/*
 * "network.js"
 */
angular.module("nispApp").service("NetworkService", ["groundwork", "$timeout", function(gw, $timeout) {
    "use strict";

    var l_this = this;

    //var l_httpMethod = "POST";
    var l_httpMethod = "GET";

    /**
     * 検索系APIの呼び出し
     *
     * 指定データについて、指定のクエリー条件によるクエリー結果を得る
     */
    this.nl_query = function(a_context, a_apiData, a_apiQueryArgs, a_after) {
        initError(a_apiData);
        var l_qContext = a_apiQueryArgs.queryContext;
        var l_logic = l_qContext.logic;
        if (l_httpMethod == "POST" && !l_logic) {
            l_logic = "query";
        }
        var l_sortKey = l_qContext.sortKey;
        var l_sortDir = l_qContext.sortDir;
        if (gw.isString(l_sortKey)) {
            l_sortKey = l_sortKey.split(',');
            for (var i in l_sortKey) {
                l_sortKey[i] = l_sortDir + l_sortKey[i];
            }
            l_qContext.sortKey = l_sortKey;
        }
        var l_apiPathName = apiPathName(a_context, a_apiData.__name, l_logic);
        var l_tmp = l_apiPathName;
        l_apiPathName = l_apiPathName.replace("{recid}", a_apiQueryArgs.recid);
        delete l_qContext.count;
        delete l_qContext.errors;
        delete a_apiQueryArgs.queryContext;
        if (l_httpMethod == "POST") {
            var l_apiRequest = apiSearchPostRequest(a_apiQueryArgs, l_qContext);
            httpPostSearch(a_context, l_apiPathName, l_apiRequest, a_apiData, a_apiQueryArgs, a_after);
        } else {
            l_apiPathName += apiSearchGetRequest(a_apiQueryArgs, l_qContext, (l_apiPathName == l_tmp));
            httpGetSearch(a_context, l_apiPathName, l_qContext, a_apiData, a_apiQueryArgs, a_after);
        }
        l_sortKey = l_qContext.sortKey;
        if (gw.isArray(l_sortKey)) {
            l_qContext.sortKey = l_sortKey.join(',').replace(/\!/g, '');
        }
        a_apiQueryArgs.queryContext = l_qContext;
    }

    /**
     * 照会系APIの呼び出し
     *
     * 指定データについて、指定のキーのレコードを得る。キーは複数指定可。
     */
    this.nl_get = function(a_context, a_apiData, a_apiDataKeys, a_after) {
        initError(a_apiData);
        var l_logic = a_apiData.queryContext ? a_apiData.queryContext.logic : undefined;
        var l_apiPathName = apiPathName(a_context, a_apiData.__name, l_logic);
        if (l_logic == "history" && gw.isArray(a_apiDataKeys) && a_apiDataKeys.length > 1) {
            l_apiPathName = l_apiPathName.replace("{recid}", a_apiDataKeys[0]) + a_apiDataKeys[1];
        } else {
            l_apiPathName += apiGetRequest(a_apiDataKeys);
        }
        return httpGet(a_context, l_apiPathName, a_apiData, a_after);
    }

    this.nl_get2 = function(a_context, a_url, a_after) {
        return httpGet2(a_context, a_url, a_after);
    }


    /**
     * 更新系APIの呼び出し(同期処理)
     *
     * 更新準備のためにトランザクションIDを取得する
     */
    this.nl_prepare = function(a_context, a_apiDataName, a_apiData, a_after) {
        try {
            return prepare(a_context, a_apiDataName, a_apiData, a_after);
        } catch (exception) {
            setError(a_apiData, 'ERROR', exception.message);
            return null;
        }
    }

    /**
     * 更新系APIの呼び出し(同期処理)
     *
     * 指定データについて、更新系処理(新規・更新・削除)を実施する。指定データの値によって何を行うかが決まる。
     * 主キーなし：新規登録
     * 主キーあり、削除フラグなし：更新処理
     * 主キーあり、削除フラグあり：削除処理
     */
    this.nl_submit = function(a_context, a_apiDataName, a_apiData, a_after) {
        initError(a_apiData);
        try {
            // 更新実行
            var l_apiPathName = apiPathName(a_context, a_apiDataName);
            var l_result = httpPost(a_context, l_apiPathName, a_apiData, a_after);
            return l_result;
        } catch (exception) {
            // エラー発生時の更新結果の取得
            var l_result = confirm(a_context, a_apiDataName, a_apiData, a_after);
            // タイムアウトや通信系のエラー
            setError(a_apiData, 'ERROR', exception.message);
            return l_result;
        }
    }

    /**
     * 更新系APIの呼び出し(同期処理)
     *
     * 交信処理を確定させ、トランザクションIDを破棄する。
     */
    this.nl_commit = function(a_context, a_apiDataName, a_apiData, a_after) {
        initError(a_apiData);
        try {
            var l_result = commit(a_context, a_apiDataName, a_apiData, a_after);
            a_context.app.fn_goNext();
            return l_result;
        } catch (exception) {
            // エラー発生時の更新結果の取得
            var l_result = confirm(a_context, a_apiDataName, a_apiData, a_after);
            // タイムアウトや通信系のエラー
            setError(a_apiData, 'ERROR', exception.message);
            return l_result;
        }
    }

    this.nl_prepare_then_submit = function(a_context, a_apiDataName, a_apiData, a_after) {
        initError(a_apiData);
        try {
            var l_result = prepareThenSubmit(a_context, a_apiDataName, a_apiData, a_after);
            return l_result;
        } catch (exception) {
            // エラー発生時の更新結果の取得
            var l_result = confirm(a_context, a_apiDataName, a_apiData, a_after);
            // タイムアウトや通信系のエラー
            setError(a_apiData, 'ERROR', exception.message);
            return l_result;
        }
    }

    this.nl_prepare_then_commit = function(a_context, a_apiDataName, a_apiData, a_after) {
        initError(a_apiData);
        try {
            var l_result = prepareThenCommit(a_context, a_apiDataName, a_apiData, a_after);
            return l_result;
        } catch (exception) {
            // エラー発生時の更新結果の取得
            var l_result = confirm(a_context, a_apiDataName, a_apiData, a_after);
            // タイムアウトや通信系のエラー
            setError(a_apiData, 'ERROR', exception.message);
            return l_result;
        }
    }

    this.nl_prepare_then_update = function(a_context, a_apiDataName, a_apiData, a_after) {
        initError(a_apiData);
        try {
            var l_result = prepareThenUpdate(a_context, a_apiDataName, a_apiData, a_after);
            return l_result;
        } catch (exception) {
            // エラー発生時の更新結果の取得
            var l_result = confirm(a_context, a_apiDataName, a_apiData, a_after);
            // タイムアウトや通信系のエラー
            setError(a_apiData, 'ERROR', exception.message);
            return l_result;
        }
    }

    this.fn_initError = function(a_apiData) {
        initError(a_apiData);
    }


    //// UTILS. ////

    function setError(a_apiData, a_name, a_message, a_status) {
        gw.app.fn_setError(a_apiData, a_name, a_message, a_status);
    }

    function apiErrorReasons(a_context, a_apiData, a_resultHeader, a_status, a_queryArgs) {
        a_apiData.queryContext.warnings = [];
        if (a_resultHeader) {
            for (var key in a_resultHeader.reasons) {
                var reason = a_resultHeader.reasons[key];
                if (reason.why.lastIndexOf('error.auth.', 0) === 0) {
                    //アクセスIDが失われたら、強制ログアウトして再ログインさせる
                    a_context.app.fn_authError(reason);
                    return false;
                }
                // WARNING = 2 
                if (reason.level == "2") {
                    a_apiData.queryContext.warnings.push({ name: "WARNING", message: reason.why });
                }
            }

            var l_warnings = true;
            if (!a_apiData.isCommit && a_apiData.queryContext.warnings != undefined && a_apiData.queryContext.warnings.length > 0) {
                var l_msg = "";
                for (var i = 0; i < a_apiData.queryContext.warnings.length; i++) {
                    l_msg = l_msg + a_apiData.queryContext.warnings[i].message + "\n";
                }
                l_warnings = window.confirm(l_msg);
                if (l_warnings == true) {
                    a_apiData.queryContext.warnings = [];
                }
            }

            if (a_resultHeader.status == "SUCCEEDED") return l_warnings;
        } else {
            if (a_status == "OK") return true;
        }

        var l_name = a_resultHeader ? a_resultHeader.status : "ERROR";
        var l_message = a_resultHeader ? a_resultHeader.message : a_status;
        if (a_resultHeader && a_resultHeader.cause) l_message += ":" + a_resultHeader.cause;
        if (!l_message) return true;

        if (l_message == "error.data.invalid") {
            l_message += "|";
            for (var key in a_resultHeader.reasons) {
                var reason = a_resultHeader.reasons[key];
                // ERROR = 4
                if (reason.level != "4") continue;
                var names = reason.what.split('.');
                var name = names[names.length - 1];
                a_apiData.queryContext.errors.push({ name: name, message: reason.why });
                var subItem = a_apiData.__model.sub_items[name];
                if (!subItem && a_queryArgs) {
                    subItem = a_queryArgs.__model.sub_items[name];
                }
                l_message += reason.why + "<br/>";
            }
        }
        if (l_message == "failed") {
            // server error
            l_message = "";
            for (var key in a_resultHeader.reasons) {
                var reason = a_resultHeader.reasons[key];
                // ERROR = 4
                if (reason.level != "4") continue;
                var names = reason.what.split('.');
                var name = names[names.length - 1];
                a_apiData.queryContext.errors.push({ name: name, message: reason.why });
                var subItem = a_apiData.__model.sub_items[name];
                if (!subItem && a_queryArgs) {
                    subItem = a_queryArgs.__model.sub_items[name];
                }
                l_message += reason.why + '<br/>';
                //var jname = subItem ? subItem.label : "";
                //l_message += name+"("+jname+"):"+reason.why+",";
            }
        }

        setError(a_apiData, "ERROR", l_message, a_status);
        return false;
    }

    /**
     * エラー情報を初期化する
     */
    function initError(a_apiData) {
        jQuery("[id^='err_']").text('');
        jQuery('input').css('background-color', '');
        jQuery('select').css('background-color', '');
        if (a_apiData.queryContext) {
            if (a_apiData.queryContext.errors) {
                a_apiData.queryContext.errors.length = 0;
            } else {
                a_apiData.queryContext.errors = [];
            }
        } else {
            a_apiData.queryContext = { errors: [], };
        }
    }

    /**
     * 更新準備のためにアクセスIDを取得する
     */
    function prepare(a_context, a_apiDataName, a_apiData, a_after) {
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "prepare");
        return httpPost(a_context, l_apiPathName, a_apiData, a_after);
    }
    /**
     * 交信処理を確定させ、トランザクションIDを破棄する。
     */
    function commit(a_context, a_apiDataName, a_apiData, a_after) {
        a_apiData.isCommit = true;
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "commit");
        var l_result = httpPost(a_context, l_apiPathName, a_apiData, a_after);
        return l_result;
    }
    /**
     * 更新確認APIの呼び出し
     *
     * submitでのRESを再度取得する。submitがタイムアウトエラーだったりした場合のハンドリング用。
     */
    function confirm(a_context, a_apiDataName, a_apiData, a_after) {
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "confirm");
        return httpGet(a_context, l_apiPathName, a_apiData, a_after);
    }

    function prepareThenSubmit(a_context, a_apiDataName, a_apiData, a_after) {
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "prepare_then_submit"); // submit
        var l_result = httpPost(a_context, l_apiPathName, a_apiData, a_after);
        return l_result;
    }

    function prepareThenCommit(a_context, a_apiDataName, a_apiData, a_after) {
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "prepare_then_submit_then_commit"); // create
        var l_result = httpPost(a_context, l_apiPathName, a_apiData, a_after);
        return l_result;
    }

    function prepareThenUpdate(a_context, a_apiDataName, a_apiData, a_after) {
        var l_apiPathName = apiPathName(a_context, a_apiDataName, "update");
        var l_result = httpPost(a_context, l_apiPathName, a_apiData, a_after);
        return l_result;
    }

    function httpGet2(a_context, a_apiPathName, a_after) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', a_apiPathName, true);

        // If specified, responseType must be empty string or "text"
        xhr.responseType = 'text';
        xhr.setRequestHeader('Content-Type', 'text/plain; charset=UTF-8');

        xhr.onload = function() {
            if (xhr.readyState === xhr.DONE) {
                a_after(xhr.responseText);
            } else {
                alert('error');
            }
        };

        xhr.send(null);
    }

    function httpGet(a_context, a_apiPathName, a_apiData, a_after) {
        gw.app.scope.done = true;
        var l_request = setHeaders(a_context, "GET", a_apiPathName);
        l_request.async = false;

        try {
            var l_result = $.ajax(l_request);

            getHeaders(a_context, l_result);
            postDone(true, a_context, l_result, a_apiData, a_after);
            return a_apiData;
        } catch (exp) {
            postDone(false, a_context, exp.message, a_apiData, a_after);
            return false;
        }
    }

    function httpPost(a_context, a_apiPathName, a_apiData, a_after) {
        gw.app.scope.done = true;
        var l_apiRequest;
        var l_name = a_apiData.__name;
        var l_apidata2 = gw.clone(a_apiData);
        delete l_apidata2.__name;
        delete l_apidata2.__model;
        delete l_apidata2.queryContext;
        delete l_apidata2.entries;
        l_apiRequest = JSON.stringify(l_apidata2, null, " ");
        var l_request = setHeaders(a_context, "POST", a_apiPathName);
        l_request.data = l_apiRequest;
        l_request.async = false;

        var l_result;
        try {
            l_result = $.ajax(l_request);
            getHeaders(a_context, l_result);
            postDone(true, a_context, l_result, a_apiData, a_after);
            return a_apiData;
        } catch (exp) {
            postDone(false, a_context, exp.message, a_apiData, a_after);
            return false;
        }
    }

    function httpGetSearch(a_context, a_apiPathName, a_qContext, a_apiData, a_apiQueryArgs, a_after) {
        return httpSearch("GET", a_context, a_apiPathName, a_qContext, a_apiData, a_apiQueryArgs, a_after);
    }

    function httpPostSearch(a_context, a_apiPathName, a_apiRequest, a_apiData, a_apiQueryArgs, a_after) {
        return httpSearch("POST", a_context, a_apiPathName, a_apiRequest, a_apiData, a_apiQueryArgs, a_after);
    }

    function httpSearch(a_method, a_context, a_apiPathName, a_apiRequest, a_apiData, a_apiQueryArgs, a_after) {
        $('#waiting_bar').html('<div class="load-bar"><div class="bar"></div><div class="bar"></div><div class="bar"></div></div>');
        gw.app.scope.done = true;
        delete a_apiData.entries;
        delete a_apiQueryArgs.entries;
        var l_apiSession = a_context.session;
        var l_request = setHeaders(a_context, a_method, a_apiPathName);
        if (a_method == "POST") {
            l_request.data = a_apiRequest;
        }

        var l_promise = jQuery.ajax(l_request);

        l_promise.then(function(result, status) {
            $('#waiting_bar').html('');
            getHeaders(a_context, l_promise);
            //setContext(a_apiData, a_apiRequest);
            //setContext(a_apiQueryArgs, a_apiRequest);
            postDone(true, a_context, result, a_apiData, a_after, a_apiQueryArgs);
            return a_apiData;
        }, function(result, status) {
            $('#waiting_bar').html('');
            //setContext(a_apiData, a_apiRequest);
            //setContext(a_apiQueryArgs, a_apiRequest);
            postDone(false, a_context, result, a_apiData, a_after, a_apiQueryArgs);
            return false;
        });
    }

    function setContext(a_apiQueryArgs, a_apiRequest) {
        if (gw.isObject(a_apiRequest)) {
            if (a_apiQueryArgs.queryContext) {
                if (a_apiRequest.p && a_apiRequest.q) {
                    gw.copy(a_apiRequest.p, a_apiQueryArgs.queryContext);
                } else {
                    gw.copy(a_apiRequest, a_apiQueryArgs.queryContext);
                }
            } else {
                if (a_apiRequest.p && a_apiRequest.q) {
                    a_apiQueryArgs.queryContext = a_apiRequest.p;
                } else {
                    a_apiQueryArgs.queryContext = a_apiRequest;
                }
            }
        }
    }

    function postDone(a_success, a_context, a_result, a_apiData, a_after, a_apiQueryArgs) {
        var l_success = true;
        var l_header = null;
        var l_data = null;
        if (a_result.responseJSON) {
            l_header = a_result.responseJSON.head;
            l_data = a_result.responseJSON.data;
        } else {
            l_header = a_result.head;
            l_data = a_result.data ? a_result.data : a_result;
        }
        if ((gw.isString(a_result) || l_header && l_header.status && l_header.status != "SUCCEEDED") || (!a_result.status || a_result.status != 200) || a_result.statusText) {
            if (!a_apiData.queryContext.errors) {
                a_apiData.queryContext.errors = [];
            }
            l_success = apiErrorReasons(a_context, a_apiData, l_header, a_result.statusText ? a_result.statusText : a_result, a_apiQueryArgs);
        }
        var l_keyName = gw.getPrimaryKeyName(a_apiData);
        if (l_success && l_data) {
            a_context.app.fn_initData(a_apiData);
            a_apiData[l_keyName] = l_data[l_keyName];
        }
        var l_after = a_after;
        $timeout(function() {
            gw.app.scope.done = false;
            if (l_after) {
                if (!l_data) {
                    l_data = gw.clone(a_apiData);
                }
                var l_afterResult;
                if (l_data.entries && l_data.entries.length) {
                    var l_count = a_apiData.count;
                    l_afterResult = l_after(l_data, a_apiData, true, l_success);
                    if (l_count !== undefined) a_apiData.count = l_count;

                    var l_entries = a_apiData.entries;
                    if (!l_afterResult)
                        l_afterResult = l_after(l_data.entries[0], a_apiData, true, l_success);
                    a_apiData.entries = l_entries;

                    if (a_apiQueryArgs && a_apiQueryArgs.queryContext)
                        a_apiQueryArgs.queryContext.count = l_data.count;
                } else {
                    l_afterResult = l_after(l_data, a_apiData, true, l_success);
                }
            }
            if (!a_context.app.fn_errorSet(a_apiData)) {
                a_context.app.fn_goNext();
            } else {
                a_context.app.nextContents = undefined;
            }
            gw.app.lg_setPaging(gw.app.scope, a_apiData, a_apiQueryArgs);
        });
    }

    function apiPathName(a_context, a_apiDataName, a_apiLogicName) {
        var l_pathElements = new Array();
        l_pathElements.push(a_context.app.fn_getBaseUrl());
        if (a_context.app.controllerURI) {
            // New API
            l_pathElements.push(a_context.app.controllerURI);
        } else {
            l_pathElements.push(a_context.app.controllerName);
            l_pathElements.push(encodeURIComponent(a_apiDataName));
            if (a_apiLogicName) {
                if (a_apiLogicName == "history") {
                    l_pathElements.push("{recid}/history/");
                } else {
                    l_pathElements.push(encodeURIComponent(a_apiLogicName));
                }
            }
        }
        var l_urlPath = l_pathElements.join("/");
        return l_urlPath;
    }

    function apiGetRequest(a_apiDataKeys, a_apiDataKeyName) {
        var l_primaryKeys = a_apiDataKeys;
        if (gw.isArray(a_apiDataKeys)) {
            l_primaryKeys = '';
            for (var i = 0; i < a_apiDataKeys.length; i++) {
                if (l_primaryKeys) l_primaryKeys = ',';
                l_primaryKeys += a_apiDataKeys[i];
            }
        }
        var l_request = '/';
        if (l_primaryKeys && l_primaryKeys !== "0")
            l_request += l_primaryKeys;

        return l_request;
    }

    function setHeaders(a_context, a_type, a_pathName, a_request) {
        var l_api = a_context.settings.network.api;
        var l_header = {
            type: a_type,
            url: a_pathName,
            dataType: l_api.dataType,
            timeout: l_api.timeout,
            contentType: l_api.contentType,
            xhrFields: {
                withCredentials: true
            },
        };
        var l_apiSession = a_context.session;
        if (l_apiSession.access_id) {
            var l_headers = {};
            l_headers[l_api.login_user_id] = l_apiSession.login_user_id;
            l_headers[l_api.access_id_key] = l_apiSession.access_id;
            if (l_apiSession.transaction_id) {
                l_headers[l_api.transaction_id_key] = l_apiSession.transaction_id;
            }
            if (l_apiSession.session_id) {
                l_headers[l_api.session_id_key] = l_apiSession.session_id;
            }
            l_header.headers = l_headers;
        }
        return l_header;
    }

    function getHeaders(a_context, a_response) {
        var l_api = a_context.settings.network.api;
        var l_session = a_context.session;
        if (a_response.getAllResponseHeaders()) {
            var l_loginUserId = a_response.getResponseHeader(l_api.login_user_id);
            if (l_loginUserId) l_session.login_user_id = l_loginUserId;

            var l_access_id = a_response.getResponseHeader(l_api.access_id_key);
            if (l_access_id) l_session.access_id = l_access_id;

            l_session.transaction_id = a_response.getResponseHeader(l_api.transaction_id_key);
            if (!l_session.transaction_id) l_session.transaction_id = undefined;

            l_session.session_id = a_response.getResponseHeader(l_api.session_id_key);
            if (!l_session.session_id) l_session.session_id = undefined;
        }
    }

    function removeInternalField(a_org) {
        var l_dst = {};
        for (var l_key in a_org) {
            if (l_key == "__model" || l_key == "__name") continue;

            var l_val = a_org[l_key];
            if (l_val === undefined) continue;

            l_dst[l_key] = l_val;
        }
        return l_dst;
    }

    function apiSearchPostRequest(a_args, a_qcontext) {
        var l_args = removeInternalField(a_args);
        var l_qcontext = gw.clone(a_qcontext);
        delete a_qcontext.result;
        l_qcontext.page--;
        return { q: l_args, p: l_qcontext, r: (a_qcontext.result ? 1 : 0) };
    }

    function apiSearchGetRequest(a_args, a_qcontext, a_qflg) {
        var l_args = removeInternalField(a_args);
        var l_json = JSON.stringify(l_args, null, " ");
        var l_qcontext = gw.clone(a_qcontext);
        delete l_qcontext.result;
        l_qcontext.page--;
        var l_paging = JSON.stringify(l_qcontext, null, " ");
        var l_requestParam = a_qflg ? ("?q=" + encodeURI(l_json) + '&') : "?";
        l_requestParam += "p=" + encodeURI(l_paging) + "&r=" + (a_qcontext.result ? 1 : 0);
        return l_requestParam;
    }

}]);