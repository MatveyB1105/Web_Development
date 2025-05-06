<?php
session_start();
require_once "Connection/connection.php";

// Проверяем, авторизован ли сотрудник
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Пожалуйста, авторизуйтесь.";
    header('Location: auth_form.php'); // Страница авторизации сотрудника
    exit();
}

// Запрашиваем все свободные заказы
$query = "
    SELECT 
        orders.Order_ID,
        orders.Product_ID,
        orders.Creation_Date,
        orders.amount,
        Product.Name AS Product_Name,
        Product.Price AS Product_Price
    FROM 
        orders
    JOIN 
        Product ON orders.Product_ID = Product.Product_ID
    WHERE 
        orders.Employee_ID IS NULL
    ORDER BY 
        orders.Creation_Date DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/free_orders.css">
    <title>Свободные заказы</title>
</head>
<body>
    <h1>Свободные заказы</h1>
    <?php if (!empty($_SESSION['message'])): ?>
    <p style="color: green;"><?= $_SESSION['message'] ?></p>
    <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Продукт</th>
            <th>Количество</th>
            <th>Дата создания</th>
            <th>Стоимость</th>
            <th>Действие</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Order_ID'] ?></td>
                    <td><?= $row['Product_Name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['Creation_Date'] ?></td>
                    <td><?= $row['Product_Price'] * $row['amount'] ?> руб.</td>
                    <td>
                        <form action="take_order.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $row['Order_ID'] ?>">
                            <button type="submit">Взять заказ</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Свободных заказов нет.</td>
            </tr>
        <?php endif; ?>
    </table>
    <form action="empl_cabinet.php" method="get">
    <button class="back-button" type="submit">Вернуться в кабинет</button>
    </form>
</body>
</html>
