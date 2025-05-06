<?php
session_start();
require_once "Connection/connection.php";

// Проверяем, установлен ли client в сессии и cookie
if (!isset($_COOKIE['client']) || !isset($_SESSION['client']['id'])) {
    $_SESSION['message'] = "Время сессии закончилось. Пожалуйста, авторизуйтесь повторно.";
    header('Location: auth_form.php');
    exit();
} else {
    $lifetime = 120;
    $id = $_SESSION['client']['id'];

    if (!empty($id)) {
        setcookie('client', $id, time() + $lifetime, '/');
    } else {
        $_SESSION['message'] = "Ошибка: неверные данные клиента.";
        header('Location: auth_form.php');
        exit();
    }
}

// Получаем имя клиента
if (!empty($id)) {
    $stmt = $conn->prepare("SELECT First_name FROM client WHERE Client_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $name = $row['First_name'];
    } else {
        $_SESSION['message'] = "Ошибка: клиент не найден.";
        header('Location: auth_form.php');
        exit();
    }
} else {
    $_SESSION['message'] = "Ошибка: неверный идентификатор клиента.";
    header('Location: auth_form.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/cabinet1.css">
</head>
<body>
    <header class="cabinet-header">
        <div class="container" style="color: black;">
			<?php
                echo '<h1>Здравствуйте, '.$name.'!</h1>';
            ?>
            <p>Выберите действие ниже:</p>
        </div>
    </header>
    <main class="cabinet-actions">
        <div class="container">
            <div class="card">
                <h2>Товары</h2>
                <p>Откройте каталог товаров и найдите то, что вам нужно.</p>
                <form action="products.php" method="POST">
                    <button class="btn" type="submit" name="show_products">Посмотреть товары</button>
                </form>
            </div>
            <div class="card">
                <h2>Создать заказ</h2>
                <p>Оформите новый заказ быстро и удобно.</p>
                <form action="create_order.php" method="POST">
                    <button class="btn" type="submit" name="create_order">Сделать заказ</button>
                </form>
            </div>
            <div class="card">
                <h2>Мои заказы</h2>
                <p>Просмотрите статус и историю ваших заказов.</p>
                <form action="show_orders.php" method="POST">
                    <button class="btn" type="submit" name="user_orders">Мои заказы</button>
                </form>
            </div>
            <div class="card">
                <h2>Главная страница</h2>
                <p>Вернитесь на главную страницу сайта.</p>
                <form action="index.php" method="POST">
                    <button class="btn" type="submit">На главную</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
