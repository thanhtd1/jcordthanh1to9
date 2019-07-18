/*
 * "Jcord::bank/Bank" ユーザ管理Logic
 */
angular.module("nispApp").controller("Jcord::bank/bank", ['$scope', '$interval', function($scope, $interval) {
    "use strict";
    var l_this = this;
    var app = $scope.$parent.app;
    app.registerControllerName("bank");

    /*
     * 画面シーケンス定義
     * 引数： コントローラ、コンテンツベース、サブシステムのパス、最初に呼ばれるロジック、シーケンス配列
     */
    app.fn_sequence(app.singleContentsBase, "bank/BK-001", "do_start", "do_exit", [
        { page: ["bank_get"], next: "do_input_upd", prev: "do_search", label: "detail" },
        { page: ["bank_upd_input"], next: "do_submit_upd", prev: "do_back", label: "input_upd" },
        { page: ["bank_upd_confirm"], next: "do_commit_upd", label: "confirm_upd" },
        { page: ["bank_upd_complete"], next: "do_search", label: "complete_upd" },
        { page: ["bank_del_complete"], next: "do_search", label: "complete_del" },
        { page: ["bank_add_input"], next: "do_submit_add", prev: "do_back", label: "input_add" },
        { page: ["bank_add_confirm"], next: "do_commit_add", label: "confirm_add" },
        { page: ["bank_add_complete"], next: "do_search", label: "complete_add" },
    ]);

    app.fn_logics(this, {
        do_start: function() {
            $scope.dialogContext = { isSetting: "1" };
            app.fn_initConstants($scope);
            $scope.availables = [{
                    id: "0",
                    label: '被統合バンク',
                },
                {
                    id: "1",
                    label: '統合されていないバンク',
                }
            ];
            $scope.Detail = l_this.Detail;
            app.fn_initData(l_this.Detail);

            $scope.initmode = undefined;
            $scope.paramKey = app.getQuery(l_this.Detail);

            app.fn_initData(l_this.CompareDetail);
            $scope.CompareDetail = l_this.CompareDetail;
            var l_primaryKey = app.getQuery(l_this.Detail);

            // パラメータ判定、新規か参照か
            if ($scope.paramKey == undefined) {
                // 新規画面へ
                $scope.initmode = "create";
                this.Detail.recid = undefined;
                app.fn_api('bank/BankWeb/add/prepare', 'lg_prepare', l_this.Detail, null);
                app.fn_call('lg_goto', "input_add");
                return false; //デフォルトの遷移をキャンセル
            }

            // 参照画面へ
            $scope.initmode = "detail";
            app.setQuery(this.Detail, undefined);
            app.fn_api('bank/Bank/get', 'lg_get', l_this.Detail, $scope.paramKey, null);
            return true;
        },
        do_exit: function() {
            return true;
        },

        // ================================================
        // === 参照画面 ===
        // ================================================

        // [参照]:一覧ボタン / 各画面の一覧に戻る用
        do_search: function() {
            // [bank_list]へ
            app.fn_call('lg_get_contents', 'bank/bank_list', null);
            return false;
        },

        // [参照]:修正ボタン
        do_input_upd: function() {
            copyData(l_this.CompareDetail, l_this.Detail);
            app.fn_api('bank/BankWeb/upd/prepare', 'lg_prepare', l_this.Detail, "done_input_upd");
            return false;
        },
        // api戻りevent
        done_input_upd: function(a_ctx, a_result) {
            // [修正入力]へ
            if (app.gw.isSuccess(a_result)) {
                app.fn_call('lg_goto', "input_upd");
            }
        },

        // [参照]:削除ボタン
        do_delete: function() {
            if (window.confirm('削除してもよろしいですか？')) {
                app.fn_api('bank/Bank/del/delete', 'lg_prepareThenCommit', l_this.Detail, "done_delete");
            }
            return false;
        },
        // api戻りevent
        done_delete: function(a_ctx, a_result) {
            // [削除結果]へ
            if (app.gw.isSuccess(a_result)) {
                app.fn_call('lg_goto', "complete_del");
            }
        },

        // ================================================
        // === 修正画面 ===
        // ================================================

        // [修正入力]:確認ボタン
        do_submit_upd: function() {
            app.fn_api('bank/BankWeb/upd/submit', 'lg_submit', l_this.Detail, "done_submit_upd");
            return false;
        },
        // api戻りevent
        done_submit_upd: function(a_ctx, a_result) {
            // [修正確認]へ
            if (app.gw.isSuccess(a_result)) {
                app.fn_call('lg_goto', "confirm_upd");
            }
        },
        // [修正入力]:戻りボタン -> 参照へ(データ再取得)
        do_back: function() {
            app.fn_api('bank/Bank/get', 'lg_get', l_this.Detail, l_this.Detail.recid, "done_get");
        },

        // [修正確認]:登録ボタン
        do_commit_upd: function() {
            app.fn_api('bank/Bank/upd/commit', 'lg_commit', l_this.Detail, "done_commit_upd");
            return false;
        },
        // api戻りevent
        done_commit_upd: function(a_ctx, a_result) {
            // [修正結果]へ
            if (app.gw.isSuccess(a_result)) {
                app.fn_call('lg_goto', "complete_upd");
            } else {
                app.fn_call('lg_goto', "input_upd");
                //return false; //デフォルトの遷移をキャンセル
            }
        },


        // ================================================
        // === 登録画面 ===
        // ================================================

        // [登録入力]:確認ボタン
        do_submit_add: function() {
            return app.fn_api('bank/BankWeb/add/submit', 'lg_submit', l_this.Detail, "done_submit_add");

        },
        // api戻りevent
        done_submit_add: function(a_ctx, a_result) {
            // [登録確認]へ
            if (app.gw.isSuccess(a_result)) {
                console.log($scope.Detail);
                app.fn_call('lg_goto', "confirm_add");
            }
        },

        // [登録確認]:登録ボタン
        do_commit_add: function() {
            return app.fn_api('bank/Bank/add/commit', 'lg_commit', l_this.Detail, "done_commit_add");
        },
        // api戻りevent
        done_commit_add: function(a_ctx, a_result) {
            // [登録結果]へ
            if (app.gw.isSuccess(a_result)) {
                app.fn_call('lg_goto', "complete_add");
            } else {
                app.fn_call('lg_goto', "input_add");
                //return false; //デフォルトの遷移をキャンセル
            }
        },
    });

    function copyData(target, source) {
        target.recid = source.recid;
        target.name = source.name;
        target.company_name = source.company_name;
        target.division = source.division;
        target.mail = source.mail;
    }

    app.fn_data(this, {
        Detail: {
            label: 'ユーザ情報',
            sub_items: {
                recid: {
                    label: 'レコードID',
                    base_model: 'string',
                },
                bankid: {
                    label: 'バンクID',
                    base_model: 'string',
                },
                cur_bankid: {
                    label: '管理バンクID',
                    base_model: 'string',
                },
                available: {
                    label: '有効フラグ',
                    base_model: 'string',
                },
                bank_name: {
                    label: 'バンク名称',
                    base_model: 'string',
                },
                short_name: {
                    label: '省略名称',
                    base_model: 'string',
                },
                ename: {
                    label: '英語名称',
                    base_model: 'string',
                },
                short_ename: {
                    label: '英語省略名称',
                    base_model: 'string',
                },
                person: {
                    label: '担当者',
                    base_model: 'string',
                },
                tel_num: {
                    label: '電話番号',
                    base_model: 'string',
                },
                fax_num: {
                    label: 'FAX番号',
                    base_model: 'string',
                },
                kind: {
                    label: 'バンク種別',
                    base_model: 'string',
                },
                row_nth: {
                    label: 'バンク並び順',
                    base_model: 'string',
                }
            }
        },
        CompareDetail: {
            label: 'ユーザ情報',
            sub_items: {
                recid: {
                    label: 'レコードID',
                    base_model: 'string',
                },
                bankid: {
                    label: 'バンクID',
                    base_model: 'string',
                },
                cur_bankid: {
                    label: '管理バンクID',
                    base_model: 'string',
                },
                available: {
                    label: '有効フラグ',
                    base_model: 'string',
                },
                bank_name: {
                    label: 'バンク名称',
                    base_model: 'string',
                },
                short_name: {
                    label: '省略名称',
                    base_model: 'string',
                },
                ename: {
                    label: '英語名称',
                    base_model: 'string',
                },
                short_ename: {
                    label: '英語省略名称',
                    base_model: 'string',
                },
                person: {
                    label: '担当者',
                    base_model: 'string',
                },
                tel_num: {
                    label: '電話番号',
                    base_model: 'string',
                },
                fax_num: {
                    label: 'FAX番号',
                    base_model: 'string',
                },
                kind: {
                    label: 'バンク種別',
                    base_model: 'string',
                },
                row_nth: {
                    label: 'バンク並び順',
                    base_model: 'string',
                },
            },
        },
    });

    // 画面シーケンスを開始する
    app.fn_sequenceStart(this, $scope);
}]);