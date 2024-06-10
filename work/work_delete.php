<?php
// var_dump($_GET);
// exit();
$id = $_GET['id'];

// DB接続　ーーーーーーーーーーーーーーーーーーーーー
include('work_functions.php');
$pdo = connect_to_db();

session_start();
// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();



// ーーーーーーーーー　SQL実行　ーーーーーーーーーー

// -- WHEREで指定しないとテーブルのデータが全滅する！！（デリートした後は永久欠番！）
// -- DELETEすると復旧できないので注意！！👇ここ！
$sql = 'DELETE FROM sleep_table WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:work_read.php");
exit();

?>