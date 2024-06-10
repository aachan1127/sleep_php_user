<?php
// include("work_functions.php");

// // var_dump($_GET);
// // exit();
// // オッケー！！
// $date = $_GET['date'];



// // DB接続
// $pdo = connect_to_db();

// // SQL実行
// $sql = 'SELECT * FROM sleep_table WHERE date=:date';
// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':date', $date, PDO::PARAM_STR);
// try {
//   $status = $stmt->execute();
// } catch (PDOException $e) {
//   echo json_encode(["sql error" => "{$e->getMessage()}"]);
//   exit();
// }

// // 今回は全体ではなく一部分だけを取得したいので fetch を使う！　fetchAll を使うと、全体が取れる！
// $record = $stmt->fetch(PDO::FETCH_ASSOC);



?> 


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>日付と曜日出力</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<form action="work_search.php" method="POST">

    <label for="datePicker">日付を選択: </label>
    <input type="date" id="datePicker" name="date_1">
    
    
        <button id="dateButton">日付選択</button>
  

    <div id="result"></div>
    <input type="hidden" name="date" id="send_date" value="">

</form>
   
    <script type="text/javascript">

        $(document).ready(function() {
        
            // $('#dateButton').on('click', function(event) {
                // 👇 変更
            $('#datePicker').change(function(event) {
                event.preventDefault();
                const selectedDate = new Date($('#datePicker').val());
                search_week(selectedDate);
            });

            function search_week(date) {
                // 曜日を配列に格納
                // const week_list = ['日', '月', '火', '水', '木', '金', '土'];
                const resultDiv = $('#result');
                resultDiv.empty();

                // 選択された日付の表示
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');;
                const day = String(date.getDate()).padStart(2, '0');
                // const weekday = week_list[date.getDay()];

                // <input type="hidden" name="id" value="">
                resultDiv.append(`${year}-${month}-${day}<br>`);

                // 1週間前の日付の表示
                const before_date = new Date(date.getTime() - (7 * 24 * 60 * 60 * 1000));
                const before_year = before_date.getFullYear();
                const before_month = String(before_date.getMonth() + 1).padStart(2, '0');
                const before_day = String(before_date.getDate()).padStart(2, '0');
                // const weekBeforeWeekday = week_list[before_date.getDay()];

                resultDiv.append(`${before_year}-${before_month}-${before_day}<br>`);
                console.log(before_year+'-'+before_month+'-'+before_day);

        $('#send_date').val(before_year+'-'+before_month+'-'+before_day);
            }

        });




    //     <div>
    //   <input type="hidden" name="id" value="">
    // </div>
    </script>
</body>
</html>







