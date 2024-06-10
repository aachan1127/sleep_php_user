<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>睡眠ログ（入力画面）</title>
    <!-- <link rel="stylesheet" href="work.css"> -->

   
</head>
<body>


    <!-- DBにデータを投げるときはここを変える　method->"POST" actionはどこにデータを飛ばすか-->
    <form action="work_create.php" method="POST">
        <fieldset>
            <legend>睡眠ログ（入力画面）</legend>
            <a href="work_read.php">一覧画面</a>
            <div>
                <!-- type->dateにすると日付入力画面が出る -->
                <!-- nameのところはPHP MyAdminのテーブルのカラム名を入力する（ここで設定した値がキーとなる） -->
                日付: <input type="date" name="date">
            </div>
            <div>
                入眠時間: <input type="datetime-local" name="sleep_start_time">
            </div>
<div>
    起床時間: <input type="datetime-local" name="sleep_end_time">
</div>
<div>
    気分: <input type="text" name="feel">
</div>
<div>
    <!-- テキストエリア　rows->４行で、cols->40文字のテキストエリアを表示させる -->
    コメント: <br>
    <textarea name="comment" id="" rows="4" cols="40"></textarea>
</div>
<!-- ーーーーーーーーーーーーーーーーーー -->

<!-- ーーーーーーーーーーーーーーーーーー -->
 <!-- tailwindcss の読み込み -->
 <!-- <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

<a href="#" class="inline-block bg-red-200 hover:bg-red-400 py-2 px-6 rounded-full shadow-md">
      ボタン1
    </a> -->


            <div>
            <button>送信</button>
           
            </div>
        </fieldset>
    </form>


<!-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->



    
</body>
</html>