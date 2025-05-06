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
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список товаров</title>
    <link rel="stylesheet" href="css/product1.css">
</head>
<body>
    <h1>Список товаров</h1>
    <a href="cabinet.php" class="back-button">Вернуться в кабинет</a>
    <div class="product-list">
        <?php
        $sql = "SELECT Product_ID, name, price, Total_Quantity FROM product";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Вывод каждого товара
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='product'>
                    <h2>" . htmlspecialchars($row['name']) . "</h2>
                    <p><strong>Цена:</strong> " . number_format($row['price'], 2) . " ₽</p>
                    <p><strong>Осталось:</strong> " . $row['Total_Quantity'] . "</p>
                </div>
                ";
            }
        } else {
            echo "<p>Товары отсутствуют.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>