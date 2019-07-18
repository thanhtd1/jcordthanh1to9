/*
 * "Jcord::member/AD-Bank" バンクユーザ申請一覧Logic
 */
angular.module("nispApp").controller("Jcord::admin/AD-Bank", function($scope) {
    "use strict";
    var l_this = this;
    var app = $scope.$parent.app;
    app.registerControllerName("AD-Bank");

    /*
     * 画面シーケンス定義
     * 引数： コントローラ、コンテンツベース、サブシステムのパス、最初に呼ばれるロジック、シーケンス配列
     */
    app.fn_sequence(app.doubleContentsBase, "admin/AD-Bank", "do_start", "do_exit", [
        { page: ["query", "list"], },
    ]);
    app.fn_logics(this, {
        do_start: function() {
            $scope.dialogContext = { isSetting: "1" };
            app.fn_initConstants($scope);

            // 検索条件
            app.fn_initData(l_this.Pager);
            l_this.Pager.lines = 15;
            $scope.Pager = l_this.Pager;

            $scope.QueryCond = l_this.QueryCond;
            $scope.Query = l_this.Query;
            l_this.QueryCond.queryContext.lines = l_this.Pager.lines;
            l_this.QueryCond.queryContext.page = 0;
            l_this.QueryCond.queryContext.sortKey = "recid";
            l_this.QueryCond.queryContext.sortDir = "";

            // 表示時検索
            app.lg_pagingQuery_api('admin/Bank/list/query', l_this.Query, l_this.QueryCond, l_this.QueryCond.queryContext.lines, 1, true);
            return false;
        },
        do_exit: function() {
            return true;
        },
        do_search: function() {
            l_this.QueryCond.queryContext.lines = l_this.Pager.lines;
            app.lg_pagingQuery_api('admin/Bank/list/query', l_this.Query, l_this.QueryCond, l_this.QueryCond.queryContext.lines, 1, true);
            return true;
        },
        do_detail: function(a_context, a_recid) {
            app.fn_api('admin/Bank/query', 'lg_get_contents', 'bank/BK-001', 'bank/Detail', a_recid);
        },
        // 追加ボタン
        do_create: function() {
            app.fn_api('bank/BankWeb/add/prepare', 'lg_get_contents', 'bank/BK-001', 'bank/Detail', undefined);
        },

        // ページ制御
        do_page_count_no: function(a_context, a_line) {
            l_this.Pager.lines = a_line;
            var preg = /\D/;
            if (a_line < 5 || preg.test(a_line)) {
                l_this.Pager.lines = 5;
            }
            app.fn_call('lg_do', 'do_search');
        },
    });

    ////generated-code }}}}
    app.fn_data(this, {
        QueryCond: {
            label: 'バンクユーザ申請情報条件',
            read_only: 'true',
            sub_items: {
                name: {
                    label: 'ユーザID',
                    base_model: 'string',
                },
            },
        },
        Query: {
            label: 'バンクユーザ申請情報',
            read_only: 'true',
            sub_items: {
                recid: {
                    label: 'レコードID',
                    base_model: 'int',
                },
                request_flg: {
                    label: '変更区分',
                    base_model: 'int',
                },
                userid: {
                    label: 'ユーザID',
                    base_model: 'string',
                },
                org_name: {
                    label: '機関名称',
                    base_model: 'string',
                },
                name: {
                    label: 'ユーザ名(漢字)',
                    base_model: 'string',
                },
                request_date: {
                    label: '移植年月日',
                    base_model: 'date',
                },
                request_userid: {
                    label: '申請者',
                    base_model: 'string',
                },
            },
        },
        Pager: {
            sub_items: {
                line: {
                    base_model: 'int',
                },
            },
        },
    });
    app.fn_addConstants({
        sort: [{ k: 'recid', n: 'RECID' }, { k: 'user_name', n: 'ユーザID' }, { k: 'org_name', n: '機関名称' }, ],
    });

    app.fn_sequenceStart(this, $scope); // 画面シーケンスを開始する
});