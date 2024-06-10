<?php
// ######  セッションができてるかの練習ページ！！  ########
// セッションを読み込む
session_start();

echo "<pre>";
var_dump($_SESSION['text']);
echo "</pre>";
exit();