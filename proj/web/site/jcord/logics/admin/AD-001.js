/*
 * "Jcord::admin/AD-001" ログインLogic
 */
angular.module("nispApp").controller("Jcord::admin/AD-001", ['$scope', '$interval', function($scope, $interval) {
	"use strict";
	var l_this = this;
	var app = $scope.$parent.app;
	app.registerControllerName("AD-001");

	/*
	 * 画面シーケンス定義
	 * 引数： コントローラ、コンテンツベース、サブシステムのパス、最初に呼ばれるロジック、シーケンス配列
	 */
	app.fn_sequence(app.singleContentsBase, "admin/AD-032", "do_start", "do_exit", [
		{ page:["AD-001"],	next:"do_login",	},
	]);

	app.fn_logics(this, {
		do_start : function() {
			$scope.dialogContext = {isSetting:"1"};
			app.fn_initConstants($scope);
			$scope.Detail = l_this.Detail;
			app.fn_initData(l_this.Detail);
		},
		do_login : function() {


		}
	});
	////generated-code }}}}
	app.fn_data(this, {
		Detail: {
			label: 'ユーザ情報',
			sub_items: {
				recid: {
					label: 'レコードID',
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
				name_eng: {
					label: 'ユーザ名(英語)',
					base_model: 'string',
				},
				passwd: {
					label: 'パスワード',
					base_model: 'string',
				},
			},
		}
	});
	//// {{{{ generated-code

	app.fn_sequenceStart(this,$scope);	// 画面シーケンスを開始する
}]);