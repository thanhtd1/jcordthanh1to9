/*
 * "Jcord" Controller 
 */
angular.module("nispApp").controller("Jcord", function($scope) {
    "use strict";
    var l_ver = "0.0.0.1";
    var l_txt = l_ver;
    //var l_txt = l_ver +" $Revision: 7011 $ "+"AccID="+app.session.access_id+" TxnID="+app.session.transaction_id;
    var l_this = this;
    var app = $scope.$parent.app;
    app.registerControllerName("Jcord");

    app.fn_config({
        title: "臍帯血",
        //appHomeRelativePath: "../..",  // デフォルト値は"../.."
        initialView: "admin/AD-002",
        site: {
            Jcord: {
                //url: null
            },
        },
        settings: {
            network: {
                api: {
                    // 業務APIサーバのURL
                    base_urls: [
                        "http://localhost/jcord/proj/lib/webapi",
//		        "http://localhost/jcord/lib/webapi",
                    ],
                    // 印刷APIサーバのURL
                    printApi_url: "http://localhost/jcord/proj/lib/webapi",
                },
                fileApi: {
                    //ファイルAPIサーバのURL
                    base_url: "http://localhost",
                },
            }
        }
    });

    app.fn_logics(this, {
        do_on_load: function() {
            hideAclElement();
            $scope.fn_displayEvent();
        },
    });

    // 共通設定
    app.fn_addConstants({
        // common
        sex: [{ k: 1, n: '男' }, { k: 2, n: '女' }, ],
        reg_stat: [{ k: 0, n: '公開' }, { k: 1, n: '公開保留' }, { k: 3, n: '申込確定' }, { k: 4, n: '申込' }, { k: 8, n: '公開取消' }, { k: 9, n: '供給' }, { k: 10, n: '移植実施報告' }, ],
        trans_flag: [{ k: 0, n: '' }, { k: 1, n: '移植実施' }, { k: 2, n: '移植未実施' }, ],
        receipt_bankid: [
            { k: 1, n: '日本赤十字社北海道さい帯血バンク' },
            { k: 2, n: '宮城さい帯血バンク' },
            { k: 3, n: '日本赤十字社関東甲信越さい帯血バンク' },
            { k: 4, n: '東京臍帯血バンク' },
            { k: 5, n: '神奈川臍帯血バンク' },
            { k: 6, n: '東海大学さい帯血バンク' },
            { k: 7, n: '中部さい帯血バンク' },
            { k: 8, n: '日本赤十字社近畿さい帯血バンク' },
            { k: 9, n: '兵庫さい帯血バンク' },
            { k: 10, n: '中国四国臍帯血バンク' },
            { k: 11, n: '日本赤十字社九州さい帯血バンク' },
        ],
        request_flg: [{ k: 0, n: '新規' }, { k: 1, n: '変更' }, { k: 2, n: '削除' }, ],
    });

    app.fn_data(this, {
        "LoginUser": {
            "label": "ログインユーザ",
            "base_model": "user",
            "multiple": false,
            "sub_items": {
                "login_date": {
                    "base_model": "japanese_date"
                },
                "login_time": {
                    "base_model": "string"
                },
            }
        }
    });
    app.fn_setMessage({
        "min_length4": "4文字以上",
        "max_length10": "10文字以下",
        "max_length20": "20文字以下",
        "max_length40": "40文字以下",
        "to_bigger_than_from": "開始より終了を後の日付にしてください",
        "different_userId": "ユーザIDと異なるパスワードを指定してください",
        "different_password": "今のパスワードと異なるものを指定してください",
        "same_password": "同じパスワードを指定してください",
        "error.auth_user_password.passwd_not_matched": "パスワードが一致しません",
    });

    /**
     * ログインユーザの権限に応じて、メニュー項目を隠す。
     */
    function hideAclElement() {
        if ($('ul.jd_menu').length == 0) return false;

        return false;
    }

    this.fn_displayEvent = function() {
        $('tr').addClass('off');
        $('tr').mouseover(function(ev) {
            $(this).removeClass('off');
            $(this).addClass('on');
        });
        $('tr').mouseout(function(ev) {
            $(this).removeClass('on');
            $(this).addClass('off');
        });
        app.version = l_txt;
        //jQuery("#debug_msg").val(l_txt);
        hideAclElement();
        return $('span').length;
    };

    function loadLoginUser() {
        if (app.loginUser) return;
        $('#err_ERROR').text('');
        $('#err_ERROR').hide();
        $('.messageArea').hide();
        if (app.fn_loginUser(l_this.LoginUser)) {
            l_this.LoginUser.login_dateTime = app.gw.getNowDateTimeJp();
            hideAclElement();
            l_this.fn_displayEvent();
        } else {
            var l_api = app.context.settings.network.api;
            if (l_retryCount++ < l_api.retry_count) {
                $('#err_ERROR').text('');
                $('.messageArea').hide();
                setTimeout(loadLoginUser, l_api.timeout);
            } else {
                $('#err_ERROR').show();
                $('.messageArea').show();
            }
        }
    }

    var l_retryCount = 0;
    $scope.loginUser = this.LoginUser;
    app.loginUserOrg = this.LoginUser;
    app.topScope = $scope;
    app.fn_displayEvent = this.fn_displayEvent;

    $scope.$on('$includeContentLoaded', function(event) {
        //loadLoginUser();
        l_this.fn_displayEvent();
        hideAclElement();
    });
    app.fn_setFuncParams('topPage', "admin/top");
})