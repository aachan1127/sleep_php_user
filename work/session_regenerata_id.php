<?php
// ーーーーーー　セッションの読み込み　ーーーーーーーー
session_start();

// ーーーーーー　idを取得　ーーーーーーー
// セッションidが 検証 -> アプリケーションのところに保存される

$old_session_id = session_id();
var_dump($old_session_id);

// ーーーーーー　再生成　ーーーーーーーー
// ()の中身を true にしないと、新しい id を発行しても、前の id で入れるようになってしまう。
session_regenerate_id(true);
$new_session_id = session_id();

// リロードするたびに新しい id が発行されているか確認！
// var_dump($new_session_id);
// exit();
//  オッケー！！

// 新旧のidを画面に表示して更新されていることを確認
echo "<p>旧id: {$old_session_id}</p>";
echo "<p>新id: {$new_session_id}</p>";
exit();