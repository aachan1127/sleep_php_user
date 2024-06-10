<?php
include("work_functions.php");
$pdo = connect_to_db();
// var_dump($_GET);
// exit();

// $date = $_POST["date"];
// var_dump($_POST);
// exit();

// SELECT * FROM sleep_table WHERE date BETWEEN '2020-07-01 00:00:00' AND '2020-07-31 23:59:59
$date = $_POST['date'];
$date_1 = $_POST['date_1'];

$sql ="SELECT * FROM sleep_table WHERE date BETWEEN '" . $date. "' AND '" . $date_1."'";

// var_dump($_POST['date']);
// exit();

$stmt = $pdo->prepare($sql);


try {
    $status = $stmt->execute();
  } catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
  }
  
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);
// exit();
  






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
    <a href="work_input.php">入力画面</a>
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


<!-- ############################ -->

</body>

</html>

