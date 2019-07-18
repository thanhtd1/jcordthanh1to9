/*
 * "main.js" provides the event-handling features, as an Angular controller
 */
var __global_app;
angular.module("nispApp").controller("Application", ["ModelService", "DataService", "Validators", "Normalizers", "CommonModels", "LogicService", "ContentsService", "groundwork", "$timeout", '$sce', "$http",
    function(model, data, validators, normalizers, common_models, logic, contents, gw, $timeout, $sce, $http) {
        "use strict";

        var l_this = this;

        //
        // VIEW ATTRS.
        //

        this.title = undefined; // <title>タグ

        this.appHomeRelativePath = undefined; // 業務サービスのcontents位置からこのアプリケーションのホーム位置までの相対パス

        this.singleContentsBase = undefined; // １パーツ画面のベーステンプレート
        this.doubleContentsBase = undefined; // ２パーツ画面のベーステンプレート
        this.tripleContentsBase = undefined; // ３パーツ画面のベーステンプレート

        this.view = undefined; // 画面
        this.contentsBase = undefined; // 画面ベース
        this.contents = undefined; // 画面パーツ

        this.businesses = undefined; // 他の業務サービスのエンドポイント定義
        /* ※以下記述サンプル（※実際はfn_configの引数で準備する）
         * this.businesses: {
         *   kotsuzui: {  // 業務サービス識別名
         *	 url: null  // 業務サービスURL（※「このアプリ内」の場合は、指定しない（ appHomeRelativePath + "/business/" + business_service_name となる））
         *   },
         *   recip: {
         *	 url: "/contents/business/recip/"  // ※「同一ドメイン内、他アプリ」の場合は、パスのみ指定
         *   },
         *   zaidan: {
         *	 url: "http://localhost:8080/contents/business/zaidan/"  // 「他ドメイン」の場合は、フルURL指定
         *   }
         * }
         */

        this.models = common_models;

        this.model = model;
        this.data = data;
        this.gw = gw;
        gw.app = this;

        this.logic = logic;

        this.$timeout = $timeout;
        this.$sce = $sce;
        this.$http = $http;

        //	model.lg_registerApp(this);

        gw.setJapaneseNengos(this); // 和暦の年号配列

        //
        // CONTEXT & SESSION
        //

        this.context = {
            // Settings
            settings: {
                // {#498} settingsの内容を定める
                network: {
                    api: {
                        iframe_urls: [
                            "https://localhost:8080/sample_app/test.html",
                            "https://localhost:8180/sample_app/test.html",
                        ],
                        base_urls: [
                            "http://localhost:8080/sample_app",
                            "http://localhost:8180/sample_app",
                        ],
                        next_url_index: 0,
                        contentType: "application/json; charset=UTF-8",
                        timeout: 300 * 1000, // ミリ秒
                        dataType: "json",

                        transaction_id_key: "X-Nisp-Transaction-Id",
                        access_id_key: "X-Nisp-Access-Id",
                        session_id_key: "X-Nisp-Session-Id",
                        login_user_id: "X-Nisp-User-Id",

                        retry_count: 10,
                        // 印刷APIサーバのURL
                        printApi_url: "http://localhost:8010",
                    },
                    fileApi: {
                        base_url: "http://localhost:8080",
                    },
                },
                cookie: {
                    session_id_key: "nisp-session-id",
                    access_id_key: "nisp-access-id",
                    max_age: 1000000,
                }
            },

            session: {
                login_user_id: undefined,
                transaction_id: undefined,
                access_id: undefined,
                session_id: undefined,
            },
            app: this,
        };

        this.session = this.context.session;

        this.setScope = function(a_scope, a_controller, a_topPage) {
            l_this.scope = a_scope;
            l_this.loadCount = 0;
            l_this.nextContents = "";
            l_this.curController = a_controller;
            l_this.models.setScope(a_scope);
            if (a_topPage) {
                l_funcParams.topPage = a_topPage;
            }
        }


        /**
         * 	ログインユーザ
         */
        this.loginUser = undefined;
        this.loginUserOrg = undefined;
        this.curRole = undefined;

        this.fn_loginUser = function(a_loginUserCond, a_after) {
            if (l_this.loginUser) return l_this.loginUser;

            l_this.fn_api('Main/UserInfo/uniquery', 'lg_query', l_this.loginUserOrg, a_loginUserCond, 1, a_after);
        }

        this.fn_loginUserPrimaryKey = function() {
            if (!l_this.loginUser) return undefined;
            return gw.getPrimaryKey(l_this.loginUser);
        }

        this.fn_authError = function(a_reason) {
            l_this.logoutReason = a_reason.why;
            //		if (l_this.logoutReason != 'error.auth.login') {
            l_this.fn_click('lg_get_contents', 'app_menu/AUTH_902');
            //		}
        }


        //
        // CONFIG.
        //

        /*
         * アプリ初期設定
         *
         * 業務サービスのベースのControllerで、一度だけ呼び出す
         */
        this.fn_config = function(a_configs) {
            applyConfigs(a_configs);
            model.lg_validators(validators);
            model.lg_models(common_models);
            model.lg_normalizers(normalizers);
            model.lg_visualizers(normalizers);
        };

        function applyConfigs(a_configs) {
            applyViewAttrs(a_configs);
            applyContentsBases(l_this.appHomeRelativePath);
            applyInitialView(a_configs.initialView);
            applyBusinesses(a_configs.businesses);
            applySettings(a_configs.settings);
        }

        function applyViewAttrs(a_configs) {
            l_this.title = a_configs.title;
            l_this.appHomeRelativePath = gw.exists(a_configs.appHomeRelativePath) ? a_configs.appHomeRelativePath : "../..";
        }

        function applyContentsBases(a_appHomeRelativePath) {
            l_this.singleContentsBase = a_appHomeRelativePath + "/contents/html/base1.html";
            l_this.doubleContentsBase = a_appHomeRelativePath + "/contents/html/base2.html";
            l_this.tripleContentsBase = a_appHomeRelativePath + "/contents/html/base3.html";
        }

        var l_initialView = undefined;

        function applyInitialView(a_initialView) {
            var l_urlParam = l_this.fn_urlParam("c");
            l_initialView = gw.exists(l_urlParam) ? l_urlParam : a_initialView;
            l_this.fn_call("lg_get_contents", l_initialView);
        }
        this.fn_getInitialView = function() {
            return l_initialView;
        }
        this.fn_retryLogin = function() {
            l_this.fn_call("lg_get_contents", l_this.fn_getInitialView());
        }

        this.fn_getNextBaseUr = function() {
            var l_api = l_this.context.settings.network.api;
            l_api.next_url_index++;
            if (l_api.base_urls.length <= l_api.next_url_index) {
                l_api.next_url_index = 0;
            }
            return l_this.fn_getBaseUrl();
        }
        this.fn_getBaseUrl = function() {
            var l_api = l_this.context.settings.network.api;
            if (l_this.__printApi) {
                l_this.__printApi = false;
                return l_api.printApi_url;
            }
            var l_base_urls = l_api.base_urls;
            var l_index = l_api.next_url_index;
            return l_base_urls[l_index];
        }
        this.fn_getPrintApiUrl = function() {
            return l_this.context.settings.network.api.printApi_url;
        }
        this.__printApi = false;
        this.fn_getFileApiUrl = function() {
            var l_url = l_this.context.settings.network.fileApi.base_url;
            return l_url;
        }

        /*
         * 呼び出しURLのQUERY_STRINGより、指定パラメータの値を取得する
         */
        this.fn_urlParam = function(a_paramName) {
            var l_urlParam = location.search.match(a_paramName + "=(.*?)(&|$)");
            return gw.exists(l_urlParam) ? decodeURIComponent(l_urlParam[1]) : undefined;
        };

        this.fn_urlContains = function(a_path) {
            var l_urlPath = location.search.match(a_path);
            return gw.exists(l_urlPath);
        }

        function applyBusinesses(a_businesses) {
            l_this.businesses = a_businesses;
        }

        function applySettings(a_settings) {
            if (a_settings.network) {
                if (a_settings.network.api) {
                    var l_api = a_settings.network.api;
                    var l_thisApi = l_this.context.settings.network.api;
                    for (var l_key in l_api) {
                        var l_val = l_api[l_key];
                        if (l_val !== undefined) {
                            l_thisApi[l_key] = l_val;
                        }
                    }
                }
                if (a_settings.network.fileApi) {
                    var l_api = a_settings.network.fileApi;
                    var l_thisApi = l_this.context.settings.network.fileApi;
                    for (var l_key in l_api) {
                        var l_val = l_api[l_key];
                        if (l_val !== undefined) {
                            l_thisApi[l_key] = l_val;
                        }
                    }
                }
            }
        }

        //
        // CONTENTS
        //
        var l_currentContents = {};

        this.fn_contents = function(a_contentsBase, a_contentsPath, a_contentsNames) {
            l_currentContents.base = a_contentsBase;
            l_currentContents.path = a_contentsPath;
            l_currentContents.name = a_contentsNames;

            logic.setCurrentPath(a_contentsPath);
            l_this.contentsBase = a_contentsBase;
            var l_contentsPaths = [];
            if (!a_contentsNames) {
                l_contentsPaths.push(a_contentsPath);
            } else {
                for (var i = 0; i < a_contentsNames.length; i++) {
                    l_contentsPaths.push(a_contentsPath + '/' + a_contentsNames[i]);
                }
            }
            l_this.fn_call("lg_get_contents", l_contentsPaths);
        };

        this.fn_getInitialContents = function() { return l_currentContents; }

        var l_returnContents = {};
        this.fn_setReturnContents = function(a_place, a_contents) {
            l_returnContents[a_place] = a_contents;
        }
        this.fn_getReturnContents = function(a_place, a_contents) {
            if (l_returnContents[a_place]) return l_returnContents[a_place];
            return a_contents;
        }

        var l_viewPart = undefined;
        this.fn_setViewPart = function(a_viewPart) {
            l_viewPart = a_viewPart;
        }
        this.fn_getViewPart = function() {
            return l_viewPart;
        }
        this.fn_setHeader = function(a_headerUrl) {
            contents.headerUrl = a_headerUrl;
        }
        this.fn_setFooter = function(a_footerUrl) {
            contents.footerUrl = a_footerUrl;
        }

        this.nextContents = undefined;

        this.fn_goNext = function() {
            if (l_this.nextContents)
                l_this.fn_call("lg_get_contents", l_this.nextContents);
            l_this.nextContents = undefined;
        }

        //
        // DATA
        //

        this.fn_data = function(a_arg1, a_arg2) {
            if (gw.exists(a_arg2)) {
                var a_dataPlace = a_arg1;
                var a_models = a_arg2;
            } else {
                var a_dataPlace = undefined;
                var a_models = a_arg1;
            }
            model.lg_models(a_models, a_dataPlace);
        }

        this.fn_isSuccess = function(a_data) {
            return gw.isSuccess(a_data);
        }

        this.fn_setError = function(a_apiData, a_name, a_exception, a_status) {
            l_this.scope.done = false;
            if (a_apiData.queryContext === undefined) {
                a_apiData.queryContext = {};
            }
            if (a_apiData.queryContext.errors === undefined) {
                a_apiData.queryContext.errors = [];
            }
            if (gw.isString(a_exception)) {
                a_apiData.queryContext.errors.push({ "name": a_name, "message": a_exception, "status": a_status });
            } else {
                a_apiData.queryContext.errors.push({ "name": a_name, "message": a_exception.message, "stack": a_exception.stack, "status": a_status });
            }
            //		console.error(a_name,a_exception,a_status);
        }

        this.isTimeout = function(a_data) {
            if (!a_data.queryContext) return false;
            if (!gw.exists(a_data.queryContext.warnings)) return false;
            var warnings = a_data.queryContext.warnings;
            for (var l_idx in warnings) {
                if (warnings[l_idx].message == 'error.auth.timeout') return true;
            }
            return false;
        }

        this.fn_errorSet = function(a_data) {
            l_this.scope.done = false;
            var l_errors = {};
            var l_count = 0;
            if (l_errors['ERROR'] == undefined) {
                l_errors['ERROR'] = '';
            }

            if (a_data.queryContext && a_data.queryContext.errors) {
                for (var l_idx in a_data.queryContext.errors) {
                    var l_error = a_data.queryContext.errors[l_idx];
                    var l_name = l_error.name ? l_error.name.split('.')[0] : "ERROR";
                    var l_keys = l_error.message.split('|');
                    var l_key = l_keys[0];
                    var l_msg = (l_messages[l_key]) ? l_messages[l_key] : l_key;
                    if (l_keys.length > 1) {
                        l_msg += " " + l_keys[1];
                    }
                    //TODO: messageが重複する場合、判別関数を返す。
                    if (l_msg && l_msg != "null") {
                        //l_errors[l_name] = ((l_errors[l_name] == undefined) ? "" : l_errors[l_name])+ " : " + l_msg ;
                        l_errors[l_name] = l_msg;
                        if (l_name != 'ERROR') {
                            // Javascriptのエラーを上部に入れる
                            l_errors['ERROR'] += l_name + ':' + l_msg + '<br/>';
                        }

                    }
                    if (l_name && l_name != "null") {
                        l_count++;
                    }
                }
            }

            // 初期化
            $timeout(function() {
                jQuery('.messageArea').hide();
                var l_top = jQuery('#err_ERROR');
                l_top.html('');
                for (var l_name in l_errors) {
                    var l_msg = l_errors[l_name].trim(9);
                    var l_name2 = (l_name + "");
                    l_name2 = l_name2.replace(/\$/, '');
                    var l_place = jQuery('#err_' + l_name2);
                    if (l_name == 'ERROR') {
                        var l_prev = l_place.html();
                        l_place.html(l_prev + l_msg);
                    } else {
                        // Javascriptのエラーは背景色を変える
                        var placeErrorText = jQuery('span#err_' + l_name2);
                        placeErrorText.html(l_name2 + ": " + l_msg);
                        var l_parent = l_place.parent();
                        l_parent.children('select').css('background-color', 'red');
                        l_parent.children('input').css('background-color', 'red');

                    }
                }
                jQuery('.messageArea').show();
            });
            return l_count > 0;
        }

        var l_messages = {
            //"error.validation.data.mandatory": "必須です",
            //"error.validation.data.max.digits": "最大桁数",
            "mandatory": "必須です",
            "required": "必須です",
            "han_alnumsym": "半角英数字記号のみ",
            "han_alsym": "半角英字記号のみ",
            "han_alnum": "半角英数字のみ",
            "han_alpha": "半角英字のみ",
            "numeral": "半角数字のみ",
            "integer": "整数のみ",
            "japanese_date": "年号・年月日を確認してください",
            "error.api.invalid_access_id": "ブラウザを閉じてログインしなおしてください",
            "error.data.invalid": "サーバー側での検証が失敗しました",
        };
        this.fn_setMessage = function(a_msgs) {
            gw.copy(a_msgs, l_messages);
        }

        this.fn_addConstants = function(a_constants) {
            gw.copy(a_constants, l_this.models.constants);
            return l_this.models.constants;
        }

        this.fn_initConstants = function(a_scope) {
            a_scope.constants = l_this.models.constants;
            for (var l_key in l_this.models.constants) {
                a_scope[l_key] = l_this.models.constants[l_key];
            }
        }

        // consoleが使えない場合は空のオブジェクトを設定しておく
        if (typeof window.console === "undefined") {
            window.console = {};
        }
        // console.logなどがメソッドでない場合は空のメソッドを用意する
        if (typeof window.console.log !== "function") {
            window.console.log = function() {};
        }
        if (typeof window.console.debug !== "function") {
            window.console.debug = function() {};
        }
        if (typeof window.console.error !== "function") {
            window.console.error = function() {};
        }
        if (typeof window.console.warn !== "function") {
            window.console.warn = function() {};
        }
        if (typeof window.console.info !== "function") {
            window.console.info = function() {};
        }

        //
        // LOGICS
        //

        this.fn_logics = function(a_dataPlace, a_logics) {
            logic.lg_local_logics(a_logics, a_dataPlace);
        };

        var l_queryMap = {};
        this.curQuery = undefined; // 直近で実行されたページングクエリ
        this.curDataName = undefined; // 直近のページングクエリ対象データ名
        /**
         * 各Modelごとの検索条件を保持する
         */
        this.setQuery = function(a_apiData, a_query) {
            if (!a_apiData && !a_query) {
                a_query = l_this.curQuery;
                a_apiData = l_this.curDataName;
            }
            if (!a_apiData && !a_query) return undefined;
            if (a_apiData === a_query) {
                a_query = l_this.gw.clone(a_query);
            }
            var l_name = gw.isString(a_apiData) ? a_apiData : a_apiData.__name;
            if (l_name.indexOf('/') < 0 && l_this.controllerName) l_name = l_this.controllerName + "/" + l_name;
            l_queryMap[l_name] = a_query;
            return a_query;
        }
        this.getQuery = function(a_apiData, a_query) {
            var l_name = gw.isString(a_apiData) ? a_apiData : a_apiData.__name;
            if (l_name.indexOf('/') < 0 && l_this.controllerName) l_name = l_this.controllerName + "/" + l_name;
            var l_query = l_queryMap[l_name];
            if (a_query && !gw.isObject(l_query)) {
                l_this.fn_initData(a_query);
                l_query = a_query;
            }
            return l_query;
        }

        this.fn_setQueryKey = function(a_apiDataName, a_keys) {
            var l_prev = l_this.getQuery(a_apiDataName);
            l_this.setQuery(a_apiDataName, a_keys);
            if (a_keys === null || a_keys === undefined) {
                if (!gw.isArray(l_prev) && gw.isObject(l_prev) && l_prev.__model && l_prev.queryContext) {
                    l_this.fn_initData(l_prev);
                }
            }
        }

        this.fn_clearCond = function(a_query) {
            if (!a_query) a_query = l_this.curQuery;
            l_this.fn_initData(a_query, true);
        }

        function entryExists(a_list, a_index) {
            return (a_list && gw.exists(a_list.entries) && a_index >= 0 && a_list.entries.length > a_index);
        }

        function goData(a_detail, a_offset, a_after) {
            if (!entryExists(l_this.curApiData, 0)) return false;
            for (var i in l_this.curApiData.entries) {
                if (a_detail.recid == l_this.curApiData.entries[i].recid) {
                    var index = parseInt(i, 10) + a_offset;
                    if (entryExists(l_this.curApiData, index)) {
                        var recid = l_this.curApiData.entries[index].recid;
                        return l_this.fn_api(l_this.controllerURI, 'lg_get', a_detail, recid, a_after); // New API
                    }
                }
            }
            return false;
        }

        this.fn_nextData = function(a_detail, a_after) {
            return goData(a_detail, 1, a_after);
        }
        this.fn_prevData = function(a_detail, a_after) {
            return goData(a_detail, -1, a_after);
        }

        /**
         * ページングに対応したクエリを行う
         */
        this.lg_pagingQuery_api = function(a_uri, a_apiData, a_query, a_lines, a_page, a_execQuery) {
            this.registerControllerURI(a_uri);
            l_this.lg_pagingQuery(a_apiData, a_query, a_lines, a_page, a_execQuery);
        }
        this.lg_pagingQuery = function(a_apiData, a_query, a_lines, a_page, a_execQuery) {
            if (a_execQuery == undefined) a_execQuery = true;
            l_this.curApiData = a_apiData;
            l_this.curQuery = a_query;
            l_this.curDataName = a_apiData.__name;
            l_this.setQuery(a_apiData, a_query);
            var l_context = a_query.queryContext;
            var l_query = l_this.getQuery(a_apiData);
            //		l_this.setQuery(a_apiData, undefined);
            if (gw.isObject(l_query)) {
                gw.copy(l_query, a_query);
                gw.copy(l_query.queryContext, l_context);
                a_query.queryContext = l_context;
            }
            if (a_page) {
                l_context.page = a_page;
            }
            if (a_lines) {
                l_context.lines = a_lines;
            }
            if (!a_execQuery) return;

            var l_page = l_context.page;
            delete a_apiData.count;
            l_this.fn_call('lg_query', a_apiData, a_query, l_page, function(a_result, a_data) {
                l_this.fn_call('lg_convertInbound', a_result, a_data);
                $timeout(function() {
                    if (l_this.fn_displayEvent) l_this.fn_displayEvent();

                    if (a_data.count > 0 && !gw.exists(a_data.entries)) {
                        var l_lines = a_query.queryContext.lines;
                        var l_page = Math.ceil(a_data.count / l_lines);
                        l_this.lg_pagingQuery(a_apiData, a_query, l_lines, l_page, true);
                    }
                });
            });
        }

        function compareName(a_lhs, a_rhs) {
            if (!a_lhs || !a_rhs) return false;
            return a_lhs.__name == a_rhs.__name;
        }
        this.lg_setPaging = function(a_scope, a_apiData, a_query) {
            if (!a_query) return;
            if (!compareName(l_this.curQuery, a_query)) return;

            a_scope.dataModel = a_apiData;
            a_scope.query = a_query;
            var l_context = a_query.queryContext;
            if (l_context.page * l_context.lines > l_context.count) {
                l_context.page = 1 + Math.floor(l_context.count / l_context.lines);
            }
            a_scope.page = l_context.page;
            a_scope.num = l_context.lines;
            a_scope.count = l_context.count;
        }

        /**
         * ヒストリに対して、ページングに対応したクエリを行う
         */
        this.lg_pagingQueryHistory_api = function(a_uri, a_apiData, a_query, a_lines, a_page, a_execQueryFlg) {
            if (a_query) {
                a_query.queryContext.logic = "history";
            }
            l_this.lg_pagingQuery_api(a_uri, a_apiData, a_query, a_lines, a_page, a_execQueryFlg);
        }
        this.lg_pagingQueryHistory = function(a_apiData, a_query, a_lines, a_page, a_execQueryFlg) {
            if (a_query) {
                a_query.queryContext.logic = "history";
            }
            l_this.lg_pagingQuery(a_apiData, a_query, a_lines, a_page, a_execQueryFlg);
        }

        /**
         * $scopeにヒストリに対したクエリとページングなどのコンテキスト情報を追加する
         * 他の機能から来た時にはa_queryOrgを指定する
         * queryMapにクエリがあればそれを使う
         */
        this.lg_setContextHistory_api = function(a_uri, a_scope, a_apiData, a_queryOrg, a_execQueryFlg) {
            a_execQueryFlg = (a_execQueryFlg === undefined) ? true : a_execQueryFlg;
            var lines = a_queryOrg.queryContext.lines;
            var page = a_queryOrg.queryContext.page;
            l_this.lg_pagingQueryHistory_api(a_uri, a_apiData, a_queryOrg, lines, page, a_execQueryFlg);
        }
        this.lg_setContextHistory = function(a_scope, a_apiData, a_queryOrg, a_execQueryFlg) {
            a_execQueryFlg = (a_execQueryFlg === undefined) ? true : a_execQueryFlg;
            var lines = a_queryOrg.queryContext.lines;
            var page = a_queryOrg.queryContext.page;
            l_this.lg_pagingQueryHistory(a_apiData, a_queryOrg, lines, page, a_execQueryFlg);
        }

        /**
         * $scopeにクエリとページングなどのコンテキスト情報を追加する
         * 他の機能から来た時にはa_queryOrgを指定する
         * queryMapにクエリがあればそれを使う
         */
        this.lg_setContext_api = function(a_uri, a_scope, a_apiData, a_queryOrg, a_execQueryFlg) {
            a_execQueryFlg = (a_execQueryFlg === undefined) ? true : a_execQueryFlg;
            var page = a_queryOrg.queryContext.page;
            var lines = a_queryOrg.queryContext.lines;
            l_this.lg_pagingQuery_api(a_uri, a_apiData, a_queryOrg, lines, page, a_execQueryFlg);
        };
        this.lg_setContext = function(a_scope, a_apiData, a_queryOrg, a_execQueryFlg) {
            a_execQueryFlg = (a_execQueryFlg === undefined) ? true : a_execQueryFlg;
            var page = a_queryOrg.queryContext.page;
            var lines = a_queryOrg.queryContext.lines;
            l_this.lg_pagingQuery(a_apiData, a_queryOrg, lines, page, a_execQueryFlg);
        };

        this.fn_initData = function(a_data, a_initPk, a_keepUndefined) {
            var l_sub_items = gw.getSubItems(a_data.__model);
            var l_pkName = (!a_initPk) ? gw.getPrimaryKeyName(a_data) : '';
            for (var l_key in l_sub_items) {
                if (l_pkName == l_key) continue;

                var l_model = l_sub_items[l_key];
                var l_default = gw.clone(l_model.default_value);
                if (l_default === "undefined") {
                    l_default = undefined;
                } else if (l_default === undefined && !a_keepUndefined) {
                    if (a_data[l_key]) {
                        //					if (gw.isAncestor(l_model,l_this.models.integer)) l_default = 0;
                        //					if (gw.isAncestor(l_model,l_this.models.string)) l_default = '';
                        delete a_data[l_key];
                    }
                }
                a_data[l_key] = l_default;
            }
            delete a_data.entries;
            delete a_data.count;
            delete a_data.isConverted;

            if (a_data.queryContext) {
                gw.copy(model.queryContext, a_data.queryContext);
            } else {
                gw.clone(model.queryContext, a_data.queryContext);
            }
            model.lg_visualize(a_data.__model, a_data);
        }

        this.fn_getToday = function() {
            return gw.getToday();
        }

        this.fn_getTodayJp = function() {
            return gw.convertToJapaneseDate(l_this.fn_getToday());
        }

        this.fn_getNow = function() {
            return gw.getHourMinute();
        }

        this.fn_convertCond = function(a_queryCond) {
            var cond = gw.copyModel(a_queryCond, {});
            //data.lg_convertOutboundForQuery(l_this.context, cond);
            return cond;
        }

        this.fn_setCondStart = function(a_queryCond) {
            var cond = gw.clone(a_queryCond);
            data.lg_convertOutboundForQuery(l_this.context, cond);
            cond.queryContext = a_queryCond.queryContext;
            cond.set = l_this.setCond;
            return cond;
        }
        this.setCond = function(a_fieldOrg, a_op, a_fieldName) {
            var l_op = l_operators[a_op];
            var l_value = a_fieldName ? this[a_fieldName] : undefined;
            var l_fieldName = a_fieldOrg + l_op;
            if (l_value !== undefined) {
                this[l_fieldName] = l_value;
            } else {
                delete this[l_fieldName];
            }
            delete this[a_fieldName];
            return this;
        }

        this.fn_setCond = function(a_queryCond, a_fieldOrg, a_op, a_fieldName) {
            var l_model = a_queryCond.__model;
            delete a_queryCond.__model;
            var l_queryCond = gw.clone(a_queryCond);
            a_queryCond.__model = l_model;
            l_queryCond.__model = l_model;
            if (!data.lg_convertOutboundForQuery(l_this.context, l_queryCond)) return l_queryCond;

            var l_op = l_operators[a_op];
            var l_value = a_fieldName ? l_queryCond[a_fieldName] : undefined;
            var l_fieldName = a_fieldOrg + l_op;
            if (l_value !== undefined) {
                a_queryCond[l_fieldName] = l_value;
            } else {
                delete a_queryCond[l_fieldName];
            }
            return l_queryCond;
        }
        var l_operators = {
            "=": "__eq",
            "!=": "__ne",
            "<>": "__ne",
            ">": "__gt",
            "<": "__lt",
            ">=": "__ge",
            "<=": "__le",
            "starts": "__starts",
            "ends": "__ends",
            "contains": "__contains",
            "in": "__in",
        };

        //
        // EVENT HANDLERS
        //

        // 内部呼び出し用。controllerなどはこれを使う
        this.fn_call = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };

        // New API
        this.fn_api = function(a_uri, a_logicName, a_logicArgs) {
            this.registerControllerURI(a_uri);
            var l_args = Array.apply(null, arguments);
            l_args.shift();
            var l_rtn = handleEvent(l_args);
            this.registerControllerURI(undefined);
            return l_rtn;
        };
        // 印刷 API
        this.fn_printApi = function(a_uri, a_logicName, a_logicArgs) {
            l_this.__printApi = true;
            this.registerControllerURI(a_uri);
            var l_args = Array.apply(null, arguments);
            l_args.shift();
            var l_rtn = handleEvent(l_args);
            this.registerControllerURI(undefined);
            return l_rtn;
        };

        // 以下、ブラウザなどのイベント用
        this.fn_load = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_click = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };

        // {#427} その他のエントリーポイントを追加する
        this.fn_reset = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_submit = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_unload = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_dblclick = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_focus = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_keydown = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_keypress = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_keyup = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_abort = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_error = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_blur = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_contextmenu = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_mousedown = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_mouseover = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_mousemove = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_mouseup = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_mouseout = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_resize = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_scroll = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };
        this.fn_change = function(a_logicName, a_logicArgs) {
            return handleEvent(arguments);
        };

        var l_funcParams = {
            ok: undefined,
            no: undefined,
            cancel: undefined,
            prevPage: "do_return",
            topPage: undefined,
        };
        this.fn_setFuncParams = function(a_name, a_value) {
            l_funcParams[a_name] = a_value;
        }
        this.fn_ok = function() {
            if (l_funcParams.ok) l_this.fn_call('lg_do', l_funcParams.ok);
        };
        this.fn_no = function() {
            if (l_funcParams.no) l_this.fn_call('lg_do', l_funcParams.no);
        };
        this.fn_cancel = function() {
            if (l_funcParams.cancel) l_this.fn_call('lg_do', l_funcParams.cancel);
        };
        this.fn_return = function() {
            if (l_funcParams.prevPage) l_this.fn_call('lg_do', l_funcParams.prevPage);
        };
        this.fn_topPage = function() {
            var l_topPage = l_funcParams.topPage;
            if (l_topPage) l_this.fn_call('lg_get_contents', l_topPage);
        };

        this.fn_goListTop = function() {
            if (l_this.scope.query) l_this.fn_call('lg_query', l_this.scope.dataModel, l_this.scope.query, 1);
        };
        this.fn_goListPrev = function() {
            if (l_this.scope.query) l_this.fn_call('lg_query', l_this.scope.dataModel, l_this.scope.query, l_this.scope.page > 1 ? (l_this.scope.page - 1) : 1);
        };
        this.fn_goListNext = function() {
            if (l_this.scope.query) l_this.fn_call('lg_query', l_this.scope.dataModel, l_this.scope.query, l_this.scope.page + 1);
        };
        this.fn_goListLast = function() {
            if (l_this.scope.query) l_this.fn_call('lg_query', l_this.scope.dataModel, l_this.scope.query, l_this.scope.count / l_this.scope.num);
        };


        function handleEvent(a_varargs) {
            var l_result = logic.lg_call(l_this.context, Array.apply(null, a_varargs));
            if (l_result) {
                if (l_result.contents) {
                    return applyBusinessServiceOrViewOrContents(l_result.contents);
                }
                if (l_result.result !== undefined)
                    l_result = l_result.result;
            }
            return l_result;
        }

        this.fn_applyContents = function(a_contents) {
            applyBusinessServiceOrViewOrContents(a_contents);
        }

        function applyBusinessServiceOrViewOrContents(a_contents) {
            if (!gw.exists(a_contents)) return;
            // does nothing
            if (gw.isString(a_contents)) {
                l_this.view = a_contents;
            } else if (gw.isObject(a_contents)) {
                l_this.contents = a_contents;
            } else {
                throw new gw.LogicIntegrityCollapsedException();
            }
        }

        function businessServiceUrl(a_businessServiceName) {
            var l_businessServiceDef = l_this.businesses[a_businessServiceName];
            if (gw.exists(l_businessServiceDef) && gw.exists(l_businessServiceDef.url)) {
                return l_businessServiceDef.url;
            } else {
                return l_this.appHomeRelativePath + "/business/" + a_businessServiceName + "/"; // 相対パスの動作確認
            }
        }

        function initialViewParam(a_initialView) {
            return gw.exists(a_initialView) ? "?c=" + encodeURIComponent(a_initialView) : "";
        }
        __global_app = this.context.settings.network.api.iframe_urls;

        // #645 業務切り替え時のセッション継続方法
        this.registerAccessId2Cookie = function() {
            gw.setCookie(l_this.context.settings.cookie.session_id_key, l_this.context.session.session_id, l_this.context.settings.cookie.max_age, undefined, '/');
            gw.setCookie(l_this.context.settings.cookie.access_id_key, l_this.context.session.access_id, l_this.context.settings.cookie.max_age, undefined, '/');
        }
        var l_session_id = gw.getCookie(l_this.context.settings.cookie.session_id_key);
        if (l_session_id) {
            l_this.context.session.session_id = l_session_id;
        }
        var l_access_id = gw.getCookie(l_this.context.settings.cookie.access_id_key);
        if (l_access_id) {
            l_this.context.session.access_id = l_access_id;
        }

        function delCookie() {
            gw.delCookie(l_this.context.settings.cookie.session_id_key, '/');
            gw.delCookie(l_this.context.settings.cookie.access_id_key, '/');
        }
        delCookie();

        this.fn_deleteSessiont = function() {
            delCookie();
            var session = l_this.context.session;
            var str = session.session_id;
            session.session_id = undefined;
            session.access_id = undefined;
            session.transaction_id = undefined;
            return str;
        }
        this.fn_registerRedirect = function(a_redirect) {
            l_this.redirect = a_redirect;
        }

        this.fn_logout = function(a_error) {
            var url = l_this.loginUrl + l_this.redirect;
            if (!l_this.fn_deleteSessiont() && a_error == 'error.api.unauthorized') {
                a_error = undefined;
            } else {
                url += (a_error ? ("&error=" + a_error) : "");
            }
            location.href = url;
        }

        //// ポップアップダイアログ関連 ////

        this.fn_openDialog = function(a_id, a_title) {
            l_this.scope.dialogTitle = a_title;
            $('.dialog-shadow').height($('#page').height());
            $(".dialog-shadow").show();
            $("#" + a_id).show();

            // タブ移動範囲をダイアログのinput系要素に限定する
            $("input,select,textarea,button", "#" + a_id).tabChain({ onReadyFocus: true });
        }
        this.fn_closeDialog = function(a_id) {
            $('#' + a_id).hide();
            $('.dialog-shadow').hide();
        }

        /** テキスト入力欄以外での右クリックを禁止する */
        this.fn_ignoreRightClick = function() {
            $('body').on('contextmenu', function(e) {
                e.preventDefault();
            });
            $('input[type="text"]').on('contextmenu', function(e) {
                e.stopPropagation();
            });
            $('textarea').on('contextmenu', function(e) {
                e.stopPropagation();
            });
        }

        //// 画像ファイル関連 ////

        this.trustSrc = function(src) {
            return $sce.trustAsResourceUrl(src);
        }
        this.imageUrl = function(a_url) {
            if (!gw.startsWith(a_url, "http")) {
                a_url = l_this.fn_getFileApiUrl() + "/filesrv/FileApi?fileId=" + a_url;
            }
            if (a_url.indexOf("field=undefined") >= 0) {
                return "";
            }
            var imageUrl = a_url + "&page=1&scrollbar=1&toolbar=0&navpanes=0";
            return l_this.trustSrc(imageUrl);
        }


        //// 画面シーケンス関連 ////

        this.fn_sequence = function(a_contentsBase, a_path, a_start, a_exit, a_sequence) {
            contents.setSequence(a_contentsBase, a_path, a_start, a_exit, a_sequence);
        }
        this.fn_sequenceStart = function(a_controller, a_scope) {
            l_this.setScope(a_scope, a_controller);
            contents.startSequence(a_scope);
        }

        this.registerControllerURI = function(a_controllerURI) {
            l_this.controllerURI = a_controllerURI;
        }
        this.registerControllerName = function(a_controllerName) {
            l_this.controllerName = a_controllerName;
        }

    }
]).config(function($httpProvider) {
    $httpProvider.defaults.withCredentials = true;
});

/** タブ移動範囲の制限 */
(function($) {
    $.fn.tabChain = function(option) {
        option = $.extend({}, { onReadyFocus: false }, option);
        var chains = [];
        this.each(function() {
            chains.push(this);
        });

        var init = function() {
            var first = chains[0];
            var last = chains[chains.length - 1];

            for (var elm in chains) {
                $(chains[elm]).keydown(function(event) {
                    if (event.keyCode !== 9) {
                        return;
                    }
                    if (event.target === last && !event.shiftKey) {
                        first.focus();
                        return false;
                    } else if (event.target === first && event.shiftKey) {
                        last.focus();
                        return false;
                    }
                })
            }
            if (first && option.onReadyFocus == true) {
                first.focus();
            }

        }
        init();
    };
})(jQuery);