<?php
session_start();

require_once "Connection/connection.php";

// Проверяем наличие cookie для клиента
if (!isset($_COOKIE['client'])) {
    // Если cookie 'client' не существует, считаем, что сессия истекла
    $_SESSION['message'] = "Время сессии закончилось. Пожалуйста, авторизуйтесь повторно.";
    header('Location: auth_form.php');
    exit();
} else {
    // Если cookie существует, продлеваем срок действия на 2 минуты (120 секунд)
    $lifetime = 120;
    $id = $_SESSION['client']['id'];
    setcookie('client', $id, time() + $lifetime, '/');
}
?>

<!DOCTYPE html>
<html lang="en,ru" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Мои заказы</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    function showOrders() {
        var text = document.getElementById('text').value;

        $.ajax({
          method: "GET",
          url: "get_orders.php",
          data: ({
                    text: text
                    })
        })
          .done(function( response ) {
            document.getElementById('res').innerHTML = response;
        });
    };
</script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f0f2f5;
        color: #1c1e21;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .title {
        font-size: 28px;
        font-weight: 600;
        color:rgb(3, 5, 8);
        margin-bottom: 25px;
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .back-button {
        background: #6c63ff;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 20px;
        transition: background 0.3s;
    }

    .back-button:hover {
        background:  #5548c8;
    }

    .input-box {
        margin-bottom: 25px;
    }

    .input-box input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 25px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .input-box input:focus {
        outline: none;
        border-color: #5548c8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background:  #6c63ff;
        color: white;
        font-weight: 500;
    }

    tr:hover {
        background-color: #f5f6f7;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    @media (max-width: 768px) {
        .container {
            margin: 10px;
            padding: 15px;
        }
        
        th, td {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>

</head>
<body>
<div class="container">
    <div class="title">Мои заказы</div>
    <button class="back-button" onclick="window.location.href='cabinet.php'">Назад в кабинет</button>
    <div class="content">
        <form>
            <div class="service-details">
                <div class="input-box">
                    <input type="text" id="text" placeholder="Введите ID, статус или дату создания/выполнения заказа" oninput="showOrders()"/>
                </div>
            </div>
        </form>
        <table id='res'>
            <tr>
                <th>ID заказа</th>
                <th>Дата создания</th>
                <th>Дата выполнения</th>
                <th>Статус</th>
                <th>Сотрудник</th>
            </tr>
<?php
    $user_id = $_SESSION['client']['id'];
    $query = "select * from orders where Client_ID=".$user_id." ";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)) {
          echo "<tr>";
          echo "<td>" .$row['Order_ID'] . "</td>";
          echo "<td>" . $row['Creation_Date'] . "</td>";
          echo "<td>" . $row['Perfomed_Date'] . "</td>";
          echo "<td>" . $row['status'] . "</td>";
      if(isset($row['Employee_ID'])){
      $sql = "SELECT First_Name, Middle_Name, Last_Name from employee where Employee_ID=".$row['Employee_ID'];
        $res = mysqli_fetch_array(mysqli_query($conn, $sql));
            echo "<td>" . $res['First_Name'] ." ".$res['Middle_Name']." ".$res['Last_Name']."</td>";
      }else {
      echo "<td></td>";
      };
          echo "</tr>";
    };
    echo "</table>";
?>
        </div>
    </div>
</body>
</html>
