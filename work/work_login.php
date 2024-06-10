<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>todoリストログイン画面</title>
</head>

<body>
  <!-- 👇 フォームアクション追加 -->
  <form action="work_login_act.php" method="POST">
    <fieldset>
      <legend>睡眠ログログイン画面</legend>
      <div>
        <!-- 👇 ネーム　追加 -->
        username: <input type="text" name="username">
      </div>
      <div>
        <!-- 👇 ネーム　追加 -->
        password: <input type="text" name="password">
      </div>
      <div>
        <button>Login</button>
      </div>
      <a href="work_register.php">or register</a>
    </fieldset>
  </form>

</body>

</html>