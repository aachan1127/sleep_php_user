<?php


?>
<!-- #############　jsで日付の取得の試し打ち  ################ -->



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>日付と曜日出力</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>

<script type="text/javascript">
        /*
        処理名
            曜日出力処理
        処理概要
            ボタン押下時、今日日付から1週間分の日付と曜日を出力する
        引数
            なし
        戻り値
            なし
        */
        function OutputWeekday() {
            // 曜日を配列に格納
            const week_list = ['日', '月', '火', '水', '木', '金', '土'];

            // ここーーーーーーーーーーーーーー(１週間見たい時は）そこから６タスことで終了日時を取得できる。
            // 現在時刻を取得する。
            let date = new Date();

            // div要素を取得する
            let div_result = document.getElementById('result');

            // 出力内容の初期化
            div_result.innerHTML = '';

            // カウンタの初期値をセット
            let i = 0;

            // 曜日配列の要素数分ループ処理
            while (i < week_list.length){

                // 年、月(-1に注意)、日をそれぞれ取得
                let year = date.getFullYear();
                let month = date.getMonth();
                let day = date.getDate();

                // 今日日付から1週間分の日付と曜日を出力
                div_result.innerHTML += `${year}/${month + 1}/${day} :
                    ${week_list[date.getDay()]}` +'<br>';

                // インクリメント
                i = i + 1;

                // 日付を加算する
                date.setDate(date.getDate() + 1);
            }

        }

        const currentDate = new Date();
console.log(currentDate);

    </script>

    <!-- 入出力設定 -->
    <input type="button" value="曜日出力" id="btn_title" onclick="OutputWeekday()">
    <br>
    <br>
    【日付と曜日を出力】
    <div id="result"></div>



<!-- フォームタグを作る-->
<form action="" method="">

<!-- インプットタグのデータタイプを用意 -->
<div>
    <input type="date" name="" value="" >
</div>

<!-- 横に表示するボタンを用意 -->
<button>日付選択</button>

<!-- 押したらJSで日付を取得 -->


<!-- 日付で検索する関数 -->



<!-- テストファイルで作ったのを使用 -->


<!-- 取得した日付と終了日を取得する -->

<!-- 終了日をtype="hidden"に入れる　インプットタグ -->

<div>
      <input type="hidden" name="id" value="<?= $record['id'] ?>">
    </div> 
<!-- 👆ここまでフォームタグに入れる -->


<!-- php　で日付が一致するものを表示 -->

<!-- フォームタグ　締め -->
    </form>


</body>
</html>