<?php
// var_dump($_POST);
// exit();
// オッケー！！

// ーーーーーーー　セッション読み込み　ーーーーーーー
session_start();
include('work_functions.php');

// 　ーーーーーー　データ受け取り　ーーーーーーー
// 👇work_login.php の input で送信した name をここで受け取る！
// POST で送信してるから POST で受け取る！
$username = $_POST['username'];
$password = $_POST['password'];

// ーーーーーーー　DB接続　ーーーーーーーー
$pdo = connect_to_db();


// ーーーーーーー　SQL実行　ーーーーーーーー
// username，password，deleted_atの3項目全ての条件満たすデータを抽出する．
$sql = 'SELECT * FROM users_table WHERE username=:username AND password=:password AND deleted_at IS NULL';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

// ーーーーーーー　ユーザ有無で条件分岐　ーーーーーーーー
try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

// ーーーーーーーー　データの有無で条件分岐　ーーーーーーーーー
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//   データが存在しない場合はログイン画面へ移動するリンクを表示
if (!$user) {
    echo "<p>ログイン情報に誤りがあります</p>";
    // 👇ここ、ログイン画面のURLに飛ぶようにする！
    echo "<a href=work_login.php>ログイン</a>";
    exit();
} 

// データが存在した場合はセッション変数に session_id とユーザのデータを入れ，一覧画面に移動す
else {
    // 認証成功
    $_SESSION = array();
    $_SESSION['session_id'] = session_id();
    $_SESSION['is_admin'] = $user['is_admin']; //管理者かどうかを区別する
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['id']; // ユーザーIDをセッションに保存
    header("Location:work_read.php");
    exit();
}
