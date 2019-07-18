/*
 * "Jcord::member/user_list" 臍帯血アップロードLogic
 */
angular.module("nispApp").controller("Jcord::bank/BK-060", function($scope) {
    "use strict";
    var l_this = this;
    var app = $scope.$parent.app;
    app.registerControllerName("BK-060");

    /*
     * 画面シーケンス定義
     * 引数： コントローラ、コンテンツベース、サブシステムのパス、最初に呼ばれるロジック、シーケンス配列
     */
    app.fn_sequence(app.doubleContentsBase, "bank/BK-060", "do_start", "do_exit", [
	{ page:["query","list"], },
    ]);
    app.fn_logics(this, {
	do_start : function() {
	    $scope.dialogContext = {isSetting:"1"};
	    app.fn_initConstants($scope);

	    // 検索条件
	    app.fn_initData(l_this.Pager);
	    l_this.Pager.lines = 5;
	    $scope.Pager = l_this.Pager;

	    $scope.QueryCond = l_this.QueryCond;
	    $scope.Query = l_this.Query;
	    l_this.QueryCond.queryContext.lines   = l_this.Pager.lines;
	    l_this.QueryCond.queryContext.page	  = 0;
	    l_this.QueryCond.queryContext.sortKey = "recid";
	    l_this.QueryCond.queryContext.sortDir = "";

	    // 表示時検索
	    //app.lg_pagingQuery_api('admin/Cord/list/query', l_this.Query, l_this.QueryCond, l_this.QueryCond.queryContext.lines, 1, true);

	    return false;
	},
	do_exit : function() {
	    return true;
	},

	// 検索ボタン
	do_search : function() {
	    l_this.QueryCond.queryContext.lines   = l_this.Pager.lines;
	    app.lg_pagingQuery_api('admin/CordUpload/list/query', l_this.Query, l_this.QueryCond, l_this.QueryCond.queryContext.lines, 1, true);
	    return true;
	},

	// ページ制御
	do_page_count_no : function(a_context,a_line) {
	    l_this.Pager.lines = a_line;
	    app.fn_call('lg_do', 'do_search');
	},
    });

    ////generated-code }}}}
    app.fn_data(this, {
	QueryCond: {
	    label: '臍帯血データアップロード情報条件',
	    read_only: 'true',
	    sub_items: {
		name: {
		    label: 'ユーザID',
		    base_model: 'string',
		},
	    },
	},
	Query: {
	    label: '臍帯血データアップロード情報',
	    read_only: 'true',
	    sub_items: {
		recid: {
		    label: 'レコードID',
		    base_model: 'int',
		},
		receipt_bank_no: {
		    label: '管理番号',
		    base_model: 'string',
		},
		result: {
		    label: '結果',
		    base_model: 'string',
		},
		name: {
		    label: '項目',
		    base_model: 'string',
		},
		reason: {
		    label: 'エラー内容',
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
	sort: [{k:'recid',n:'RECID'},{k:'user_name',n:'ユーザID'},{k:'org_name',n:'機関名称'},],
    });

    app.fn_sequenceStart(this,$scope);	// 画面シーケンスを開始する
});
