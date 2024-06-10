<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1週間の日付表示</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: lightgray;
        }

        tr.selected-date {
            background-color: lightblue;
            /* 選択した日付の行の背景色 */
        }
    </style>
</head>

<body>
    <a href="./work_input.php">入力画面へ</a>
    <form action="./work_read.php" method="GET">
        <label for="datePicker">日付を選択:</label>
        <input type="date" id="datePicker" name="date">
        <input type="submit" onchange="displayWeek()" value="登録">
    </form>
    <table style="display:none;">
        <thead>
            <tr>
                <th>月</th>
                <th>日</th>
                <th>曜日</th>
            </tr>
        </thead>
        <tbody id="weekDates">
        </tbody>
    </table>

    <?php
    // var_dump($_GET['date']);
    // exit();
    if (isset($_GET['date'])) {
        $selected_date = $_GET['date'];

        include('work_functions.php');
        $pdo = connect_to_db();

        // 1週間前と1週間後の日付を取得
        $week_before = date('Y-m-d', strtotime($selected_date . ' -7 days'));
        $week_after = date('Y-m-d', strtotime($selected_date . ' +7 days'));

        // データを取得するSQLクエリ
        $sql = "SELECT * FROM sleep_mood WHERE day BETWEEN :week_before AND :week_after";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':week_before', $week_before, PDO::PARAM_STR);
        $stmt->bindValue(':week_after', $week_after, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // データを表示
        if (count($results) > 0) {
            echo "<h2>選択した日の前後1週間のデータ</h2>";
            echo "<table>";
            echo "<thead><tr><th>日付</th><th>睡眠時間</th><th>気分</th></tr></thead>";
            echo "<tbody>";
            foreach ($results as $row) {
                echo "<tr><td>{$row['day']}</td><td>{$row['sleepTime']}</td><td>{$row['mood']}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "指定された期間のデータはありません。";
        }
    }
    ?>

    <script>
        function displayWeek() {
            const datePicker = document.getElementById("datePicker");
            const selectedDate = new Date(datePicker.value);
            const weekDatesBody = document.getElementById("weekDates");
            weekDatesBody.innerHTML = '';

            for (let i = -7; i <= 7; i++) {
                const currentDate = new Date(selectedDate.getTime() + (i * 24 * 60 * 60 * 1000));
                const month = currentDate.getMonth() + 1;
                const day = currentDate.getDate();
                const weekday = ['日', '月', '火', '水', '木', '金', '土'][currentDate.getDay()];
                const newRow = document.createElement("tr");

                // 選択した日付の行にCSSクラスを追加
                if (i === 0) {
                    newRow.classList.add("selected-date");
                }

                newRow.innerHTML = `
                    <td>${month}</td>
                    <td>${day}</td>
                    <td>${weekday}</td>
                `;
                weekDatesBody.appendChild(newRow);
            }

            // テーブルを表示
            document.querySelector('table').style.display = 'table';
        }
    </script>
</body>

</html>









