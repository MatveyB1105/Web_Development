<?php
session_start();
require_once "Connection/connection.php";

if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Пожалуйста, авторизуйтесь.";
    header('Location: auth_form.php');
    exit();
}

$employee_id = $_SESSION['admin']['id'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы сотрудника</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
    function showOrders() {
        var text = document.getElementById('text').value;

        $.ajax({
            method: "GET",
            url: "get_orders_emp.php",
            data: { text: text },
            beforeSend: function() {
                $('#res').html('<tr><td colspan="7" style="text-align: center">Загрузка...</td></tr>');
            }
        })
        .done(function(response) {
            document.getElementById('res').innerHTML = response;
        })
        .fail(function() {
            $('#res').html('<tr><td colspan="7" style="text-align: center; color: red">Ошибка загрузки данных</td></tr>');
        });
    };

    // Первоначальная загрузка при открытии страницы
    $(document).ready(function() {
        showOrders();
    });
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
        <div class="title">Заказы сотрудника</div>
        <form action="empl_cabinet.php" method="get">
            <button class="back-button" type="submit">Вернуться в кабинет</button>
        </form>
        <form>
            <div class="input-box">
                <div style="position: relative;">
                    <input type="text" id="text" placeholder="Поиск по ID, статусу или дате" oninput="showOrders()"/>
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #666;"></i>
                </div>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Продукт</th>
                    <th>Количество</th>
                    <th>Дата создания</th>
                    <th>Дата выполнения</th>
                    <th>Статус</th>
                    <th>Стоимость</th>
                </tr>
            </thead>
            <tbody id="res">
                <!-- Сюда будет подгружаться контент -->
            </tbody>
        </table>

    </div>
</body>
</html>
