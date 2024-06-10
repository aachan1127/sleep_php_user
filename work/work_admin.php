<?php
// ーーーーーーーーDB接続ーーーーーーーーーーーー  
include('work_functions.php');
// ーーーーーーー　セッションの読み込み　ーーーーーーー
session_start();
// ーーーーーーー　ログインしてるかのチェック　ーーーーーーー
check_session_id();
// ーーーーーーー　管理者かどうかのチェック　ーーーーーーーー
check_is_admin();

$pdo = connect_to_db();



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ページ</title>
</head>
<body>
    <h1>管理者ページ</h1>
</body>
</html>