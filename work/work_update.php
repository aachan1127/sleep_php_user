<?php
// var_dump($_POST);
// exit();
// オッケー！！


// ーーーーーーーーDB接続ーーーーーーーーーーーー
include('work_functions.php');
$pdo = connect_to_db();


// ーーーーーーー　セッションの読み込み　ーーーーーーー
session_start();

// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();

// ーーーーーーー入力チェックーーーーーーーーー
if (
    !isset($_POST['sleep_start_time']) || $_POST['sleep_start_time'] === '' ||
    !isset($_POST['sleep_end_time']) || $_POST['sleep_end_time'] === '' ||
    !isset($_POST['feel']) || $_POST['feel'] === ''||
    !isset($_POST['comment']) || $_POST['comment'] === ''
    ) {
    exit('必須項目が入力されていません');
    }

    // ーーーーーーーPOSTで送信されたデータの受け取りーーーーーーーーーーーーーー
    $sleep_start_time = $_POST["sleep_start_time"];
    $sleep_end_time   = $_POST["sleep_end_time"];
    $feel             = $_POST["feel"];
    $comment          = $_POST["comment"];
    // 👇id を受け取らないと編集内容が反映されない！！！
    $id = $_POST['id'];


// SQL実行ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
// 後ろの方　####################   必ず WHERE で id を指定すること！！！   ##########👇ここ！！！！！
$sql = 'UPDATE sleep_table SET sleep_start_time=:sleep_start_time, sleep_end_time=:sleep_end_time, feel=:feel, comment=:comment, updated_at=now() WHERE id=:id';
// ############################   WHERE をつけないと、全部のデータが更新されてしまう！！！！！！！！！！

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sleep_start_time', $sleep_start_time, PDO::PARAM_STR);
$stmt->bindValue(':sleep_end_time', $sleep_end_time, PDO::PARAM_STR);
$stmt->bindValue(':feel', $feel, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

header('Location:work_read.php');
exit();

?>