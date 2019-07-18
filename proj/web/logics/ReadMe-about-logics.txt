========================================================================

    About the "logics"

    rev.1.0.0 - 2016.4.14 - Koichi TANAKA
    rev.1.1.0 - 2016.10.4 - Katsutoshi SHINOTSUKA

========================================================================

＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿________________________________________
■ 各ファイルの説明

o main.js

    ・設計書上のEvent Function群を装備するAngular Controller

    ・JavaScript NativeあるいはAngularによるUIの仕組みと、Logicとの間の
    　“インターフェース層”という位置付けにあり、両者間の緩衝帯の役割を
    　担う

    ・現時点では、具体的には下記の役割を担っている：

        - 最終エラー処理
            ※Logic呼び出し部位がtry～catchで括られる

o logic.js

    ・data.js、contents.jsなどの処理の呼び出しディスパッチャーで、ある種
    　のワークフロー・エンジンとして振舞う
    　→contents.jsの画面シーケンスで実現

    ・将来、リッチUI対応として、業務毎のカスタムロジックの呼び出しをサ
    　ポートするかもしれない

o data.js

    ・設計書上のLogicの内、下記処理を担うAngular Component(*1)

        - APIサーバーと通信をしてデータ参照／更新を行う処理
        - データ領域に保持されるデータの操作
        - データ・バリデーション
        - データ変換

o contents.js

    ・設計書上のLogicの内、下記処理を担うAngular Component(*1)

        - コンテンツサーバーと通信をしてコンテンツ取得し、画面もしくは画
        　面パーツの描画を行う処理
        - 画面シーケンス（一覧・詳細・編集・確認・完了）を保持し、
        　「次へ」「戻る」などの画面遷移を管理する

o network.js

    ・設計書上のNetwork Function群を装備するAngular Component(*1)

o groundwork.js

    ・プリミティブなデータ処理ライブラリ

o network-ajax.js　現バージョンでは無し

    ・Network FunctionのAjax実装

        ※例えば、他にWebSocket実装の"network-websocket.js"などが想定さ
        　れる

                                                                  ◆以上
