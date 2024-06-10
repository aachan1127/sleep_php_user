<?php
// var_dump($_POST);
// exit();

// ーーーーーーーーDB接続ーーーーーーーーーーーー  work_functions.php のファイルを読み込み
include('work_functions.php');
$pdo = connect_to_db();

// ーーーーーーー　セッションの読み込み　ーーーーーーー
session_start();
// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();

// ーーーーーーー　ユーザーIDの取得　ーーーーーーー
$user_id = $_SESSION['user_id'];




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
  <style>
    div{padding: 10px;font-size:16px;}
    td{border: 1px solid blue;}
  </style>
</head>

<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <fieldset>
    <legend>睡眠ログ（一覧画面）</legend>
    <p>ようこそ、<?=$_SESSION['username']?>さん！！！</p>
    <a href="work_input.php">入力画面</a>
    <a href="work_logout.php">logout</a>
    <a href="work_admin.php">管理者ページ</a>
    <a href="work_kankei.php">相関関係を調べる</a>
    <table>
      <thead>
        <tr>
          <th>睡眠開始時間</th>
          <th>睡眠終了時間</th>
          <th>気分</th>
          <th>コメント</th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>

  <p id="feel_n"></p>

  <script>
    // JSONの受け取り
    const data = '<?=$json?>';
    console.log(data);

    // JSONをオブジェクトに変換
    const obj = JSON.parse(data);
    console.log(obj);

    // 気分の値を順番通りに取得
    const feelValues_array = obj.map(record => parseInt(record.feel, 10));
    console.log(feelValues_array);

    // 睡眠時間の値を計算して取得
    const sleepHours_array = obj.map(record => {
      const startTime = new Date(record.sleep_start_time);
      const endTime = new Date(record.sleep_end_time);
      const diffMs = endTime - startTime;
      return diffMs / (1000 * 60 * 60);
    });
    console.log(sleepHours_array);
  </script>

  <div>
    <canvas id="correlationChart"></canvas>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

  <script>
    $(document).ready(function() {
      drawCorrelationChart(sleepHours_array, feelValues_array);
    });

    function drawCorrelationChart(xValues, yValues) {
      const ctx = document.getElementById('correlationChart').getContext('2d');

      new Chart(ctx, {
        type: 'scatter',
        data: {
          datasets: [{
            label: '睡眠時間と気分の相関',
            data: xValues.map((x, i) => ({ x: x, y: yValues[i] })),
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            x: {
              type: 'linear',
              position: 'bottom',
              title: {
                display: true,
                text: '睡眠時間 (時間)'
              }
            },
            y: {
              title: {
                display: true,
                text: '気分'
              },
              beginAtZero: true,
              max: 10
            }
          }
        }
      });
    }
  </script>
</body>

</html>