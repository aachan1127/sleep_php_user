<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>睡眠ログユーザ登録画面</title>
</head>

<body>
  <form action="work_register_act.php" method="POST">
    <fieldset>
      <legend>睡眠ログユーザ登録画面</legend>
      <div>
        username: <input type="text" name="username">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button>Register</button>
      </div>
      <a href="work_login.php">or login</a>
    </fieldset>
  </form>

</body>

</html>