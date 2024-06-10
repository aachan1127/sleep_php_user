<?php
session_start();
include("work_functions.php");
// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();

// var_dump($_GET);
// exit();
// オッケー！！
$id = $_GET['id'];

// DB接続
$pdo = connect_to_db();

// SQL実行
$sql = 'SELECT * FROM sleep_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// 今回は全体ではなく一部分だけを取得したいので fetch を使う！　fetchAll を使うと、全体が取れる！
$record = $stmt->fetch(PDO::FETCH_ASSOC);

// echo "<pre>";
// var_dump($record);
// echo "</pre>";
// exit();
// オッケー！！

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>睡眠ログ（編集画面）</title>
</head>

<body>
  <form action="work_update.php" method="POST">
    <fieldset>
      <legend>睡眠ログ（編集画面）</legend>
      <a href="work_read.php">一覧画面</a>
      <div>
        <!-- 👇 ーーーーーーここ編集するとこーーーーーーーーー -->
        睡眠開始時間: <input type="datetime-local" name="sleep_start_time" value="<?= $record['sleep_start_time'] ?>">
      </div>
      <div>
        起床時間: <input type="datetime-local" name="sleep_end_time" value="<?= $record['sleep_end_time'] ?>">
      </div>
      <div>
    気分: <input type="text" name="feel" value="<?= $record['feel'] ?>">
</div>
<div>
    <!-- テキストエリア　rows->４行で、cols->40文字のテキストエリアを表示させる -->
    コメント: <br>
    <!-- テキストエリアは Value属性がない　　代わりに、テキストエリアの内容はタグの間に記述する👇 -->
    <textarea type="text" name="comment" rows="4" cols="40"><?= $record['comment'] ?></textarea>
</div>
      <div>
      <input type="hidden" name="id" value="<?= $record['id'] ?>">
    </div>
    <!-- 👆 ーーーーーーーーーーここまでーーーーーーーーーーーーー -->
      <div>
        <button>送信</button>
      </div>
    </fieldset>
  </form>

</body>

</html>