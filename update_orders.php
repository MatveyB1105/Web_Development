<?php
session_start();
require_once "Connection/connection.php";

// Проверяем, авторизован ли сотрудник
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Пожалуйста, авторизуйтесь.";
    header('Location: auth_form.php'); // Страница авторизации сотрудника
    exit();
}

// Получаем ID текущего сотрудника из сессии
$employee_id = $_SESSION['admin']['id'];

// Запрашиваем заказы, которые принадлежат этому сотруднику
$query = "
    SELECT 
        orders.Order_ID,
        orders.Product_ID,
        orders.Creation_Date,
        orders.status,
        orders.amount,
        Product.Name AS Product_Name
    FROM 
        orders
    JOIN 
        Product ON orders.Product_ID = Product.Product_ID
    WHERE 
        orders.employee_id = ?
    ORDER BY 
        orders.Creation_Date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/free_orders.css">
    <title>Управление заказами</title>
</head>
<body>
    <h1>Управление заказами</h1>
    <h3>Сотрудник: <?= $_SESSION['admin']['last_name'] ?> <?= $_SESSION['admin']['first_name'] ?></h3>

    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Продукт</th>
            <th>Количество</th>
            <th>Дата создания</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Order_ID'] ?></td>
                    <td><?= $row['Product_Name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['Creation_Date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php if ($row['status'] != 'Завершен'): ?>
                            <form action="update_order_status.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $row['Order_ID'] ?>">
                                <button type="submit">Завершить</button>
                            </form>
                        <?php else: ?>
                            Завершён
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Заказов нет.</td>
            </tr>
        <?php endif; ?>
    </table>
    <form action="empl_cabinet.php" method="get">
    <button class="back-button" type="submit">Вернуться в кабинет</button>
    </form>
</body>
</html>
