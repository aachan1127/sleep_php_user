<?php
// var_dump($_POST);
// exit();

// ーーーーーーーーDB接続ーーーーーーーーーーーー  work_functions.php のファイルを読み込み
include('test_functions.php');
$pdo = connect_to_db();

// ーーーーーーー　セッションの読み込み　ーーーーーーー
session_start();
// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();


// ユーザーIDの取得
$user_id = $_SESSION['user_id'];

// var_dump($user_id);
// exit();




// ーーーーーーーーーSQL 作成＆実行ーーーーーーーーーーーー
// ここも毎回同じだからコピーして持ってくる（createから）
// FROMの後のテーブル名は自分で作ったやつに変える

$sql = 'SELECT * FROM sleep_table WHERE user_id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);


try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// 読み込むときは「ユーザが入力したデータ」を使用しないのでバインド変数は不要
// (バインド変数　＝＞　変なコマンド入れてハッキングしてくるの防止するやつ。)
// 　↓ こういうの。
// $stmt->bindValue(':sleep_start_time', $sleep_start_time, PDO::PARAM_STR);


// ーーーーーーーーーーSQL実行の処理ーーーーーーーーーーーー
// ここも毎回同じ、コピーして持ってくる
// fetchAll は、配列の中身を繰り返し撮ってきてね　という関数。

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



// ーーーーーーーーーー睡眠時間の計算ーーーーーーーーーーーーー
// sleep_start_timeとsleep_end_timeの差を計算して新しいフィールドに追加


// echo('<pre>');
// var_dump($result);
// exit();
// echo('</pre>');

// ######## ここJSON に値を渡したい時に使う #############
// 毎回同じなのでコピーして使う！！！！
$json = json_encode($result,JSON_UNESCAPED_UNICODE);
// ##################################################

// ここで使われている　.=　は、前に使われていた$output = ""　に追加したいので . がつく。+=みたいな感じ
$output = "";
foreach ($result as $record) {
  $output .= "
    <tr>
      <td>{$record["sleep_start_time"]}</td>
      <td>{$record["sleep_end_time"]}</td>
      <td>{$record["feel"]}</td>
      <td>{$record["comment"]}</td>
      <td>
        <a href='work_edit.php?id={$record["id"]}'>編集</a>
      </td>
      <td>
        <a href='work_delete.php?id={$record["id"]}'>削除</a>
      </td>
    </tr>
  ";
}

?>



<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>睡眠ログ（一覧画面）</title>
  
<!-- <script scr="https://cdnjs.com/libraries/Chart.js"></script> -->


  <style>
    div{padding: 10px;font-size:16px;}
    td{border: 1px solid blue;}
  </style>
</head>

<body>
  <!-- jqueryの読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


  <fieldset>
    <legend>睡眠ログ（一覧画面）</legend>
    <p>ようこそ、<?=$_SESSION['username']?>さん！！！</p>
    <a href="work_input.php">入力画面</a>
    <a href="work_logout.php">logout</a>
    <table>
      <thead>
        <!-- tr は１行で表示させたい時に使う！ -->
      <tr>
        <!-- th は一つのセル（同じ内容（配列の中身）を縦に並べたい時に使う -->
          <th>睡眠開始時間</th>
          <th>睡眠終了時間</th>
          <th>気分</th>
          <th>コメント</th>
        </tr>

      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
      

  <?= $output ?>


      </tbody>
    </table>
  </fieldset>

  <p id="feel_n"></p>

<script>
// JSONの受け取り
$a = '<?=$json?>';
// console.log($a);

// JSONをオブジェクトに変換
const obj = JSON.parse($a);
console.log(obj);


 // 気分の値を順番通りに連結
//  let feelValues = obj.map(record => record.feel).join(', ');
//  let feelValues_array = [];
//  feelValues_array.push(feelValues);
// console.log(feelValues_array);

// 👆これじゃ上手くいかなかったので、チャット先生に相談した結果👇これで成功した！

// ーーーーーーーーー気分の値を順番通りに取得ーーーーーーーーーーーーー
        const feelValues_array = obj.map(record => parseInt(record.feel, 10));
        console.log(feelValues_array);

        // 最初の値を<p id="feel_n"></p>に表示（オプション）
        //$("#feel_n").html(`気分: ${feelValues_array[0]}`);



//ーーーーーーーーーー睡眠時間の値を計算して取得ーーーーーーーーーーーー
// 👇ここもチャット先生に相談して出てきたやつ

// map関数 -> 配列の各要素に対して指定した関数を実行し、その結果を新しい配列として返す
// objは、PHPから渡されたJSONデータをJavaScriptオブジェクトに変換したもの(136行目で自分で作ったやつ)
// recordは、obj配列の各要素を指します
        const sleepHours_array = obj.map(record => {

          // record.sleep_start_timeから睡眠開始時間を取得し、それをJavaScriptのDateオブジェクトに変換
          // 「 Dateオブジェクト 」を使用することで、時間の計算が簡単になる
            const startTime = new Date(record.sleep_start_time);
            const endTime = new Date(record.sleep_end_time);

          // endTimeとstartTimeの差を計算
          // Dateオブジェクト同士の引き算により、結果はミリ秒単位
            const diffMs = endTime - startTime;

          // diffMsを時間単位に変換
            return diffMs / (1000 * 60 * 60); // ミリ秒を時間に変換
            // 👇
            // ｜　1000で割ると秒に変換されます（1秒 = 1000ミリ秒）
            // ｜　60で割ると分に変換されます（1分 = 60秒）
            // ｜　さらに60で割ると時間に変換されます（1時間 = 60分）
        });
        console.log(sleepHours_array);


</script>

<!-- ############################ -->

<div>
  <canvas id="myChart"></canvas>
</div>
<!-- chart.jsのCDNを読み込み -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>




<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    // type 棒-> bar , 折れ線-> line , チャート-> radar , 円-> pie , ドーナツ-> doughnut など。。。
    type: 'line',
    data: {
      labels: ['月', '火', '水', '木', '金', '土', '日'],
      datasets: [{
        // グラフの項目の名前
        label: '気分',
        data: feelValues_array,

        // ここでバーの色の調整できる
        // backgroundColor: [
        //   'red', 'blue', 'red', 'blue', 'red', 'blue', 'red'
        // ],

        // バーのふちの太さ
        borderWidth: 3
      },
      {
        type: 'bar',
        label: '睡眠時間',
        data: sleepHours_array,
        borderWidth: 1


      }
    ]
    },
    options: {
      // option: グラフのオプションを設定
      scales: {
        // y軸
        y: {
          beginAtZero: true,
          max: 12,
          ticks: {
            // 目盛の設定
            stepSize: 1,
          }
        }
      }

    }
  });


  

</script>
<!-- ーーーーーーー　ここから１週間絞り込み検索　ーーーーーーーーーー -->
<form action="work_search.php" method="POST">

    <label for="datePicker">日付を選択: </label>
    <input type="date" id="datePicker" name="date_1">
    
    
        <button id="dateButton">日付選択</button>
  

    <div id="result"></div>
    <input type="hidden" name="date" id="send_date" value="">

</form>
   
    <script type="text/javascript">

        $(document).ready(function() {
        
            // $('#dateButton').on('click', function(event) {
                // 👇 変更
            $('#datePicker').change(function(event) {
                event.preventDefault();
                const selectedDate = new Date($('#datePicker').val());
                search_week(selectedDate);
            });

            function search_week(date) {
                // 曜日を配列に格納
                // const week_list = ['日', '月', '火', '水', '木', '金', '土'];
                const resultDiv = $('#result');
                resultDiv.empty();

                // 選択された日付の表示
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');;
                const day = String(date.getDate()).padStart(2, '0');
                // const weekday = week_list[date.getDay()];

                // <input type="hidden" name="id" value="">
                resultDiv.append(`${year}-${month}-${day}<br>`);

                // 1週間前の日付の表示
                const before_date = new Date(date.getTime() - (7 * 24 * 60 * 60 * 1000));
                const before_year = before_date.getFullYear();
                const before_month = String(before_date.getMonth() + 1).padStart(2, '0');
                const before_day = String(before_date.getDate()).padStart(2, '0');
                // const weekBeforeWeekday = week_list[before_date.getDay()];

                resultDiv.append(`${before_year}-${before_month}-${before_day}<br>`);
                console.log(before_year+'-'+before_month+'-'+before_day);

        $('#send_date').val(before_year+'-'+before_month+'-'+before_day);
            }

        });


    </script>

<!-- ーーーーーーーーー　ここまで１週間絞り込み検索　ーーーーーーーーーーー -->

</body>

</html>

