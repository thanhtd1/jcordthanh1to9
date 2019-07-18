/*
 * "Jcord::admin/top" 管理者TOPLogic
 */
angular.module("nispApp").controller("Jcord::admin/top", ['$scope', '$interval', function($scope, $interval) {
	"use strict";
	var l_this = this;
	var app = $scope.$parent.app;
	app.registerControllerName("top");

	/*
	 * 画面シーケンス定義
	 * 引数： コントローラ、コンテンツベース、サブシステムのパス、最初に呼ばれるロジック、シーケンス配列
	 */
	app.fn_sequence(app.singleContentsBase, "admin/AD-002", "do_start", "do_exit", [
		{ page:["AD-002"],		label:"detail" },
	]);

	app.fn_logics(this, {
		do_start : function() {
			app.fn_initConstants($scope);

			return true;
		},
		do_exit : function() {
			return true;
		},
	});

	////generated-code }}}}
	app.fn_data(this, {
	});

	//// {{{{ generated-code
	app.fn_sequenceStart(this,$scope);	// 画面シーケンスを開始する
}]);
