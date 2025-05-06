<?php
session_start();
require_once "Connection/connection.php";

if (!isset($_COOKIE['client'])) {
    $_SESSION['message'] = "Время сессии закончилось. Пожалуйста, авторизуйтесь повторно.";
    header('Location: auth_form.php');
    exit();
}

$query = "SELECT Product_ID, Name, Price, Total_Quantity FROM Product WHERE Total_Quantity > 0";
$result = $conn->query($query);

?>

<?php if (!empty($_SESSION['message'])) : ?>
    <p class="error-message">
        <?= $_SESSION['message']; ?>
    </p>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/create_order.css">
    <title>Список товаров</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1 style="color: black;">Доступные товары</h1>

    <!-- Вывод сообщения об успешном заказе -->
    <?php if (!empty($_SESSION['order_message'])) : ?>
        <p class="success-message">
            <?= $_SESSION['order_message'] ?>
        </p>
        <?php unset($_SESSION['order_message']); ?>
    <?php endif; ?>

    <table>
        <tr>
            <th>Product ID</th>
            <th>Название</th>
            <th>Цена (руб.)</th>
            <th>Количество на складе</th>
            <th>Количество</th>
            <th>Стоимость (руб.)</th>
            <th>Действия</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['Product_ID'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td class="price" data-price="<?= $row['Price'] ?>"><?= $row['Price'] ?></td>
                <td><?= $row['Total_Quantity'] ?></td>
                <td>
                    <input type="number" class="amount" name="amount" min="1" max="<?= $row['Total_Quantity'] ?>" value="1" required>
                </td>
                <td class="total-price">0</td>
                <td>
                    <form action="create_order_user.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['Product_ID'] ?>">
                        <input type="hidden" class="ajax-amount" name="amount" value="1">
                        <button type="submit" class="take-button">Взять</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="cabinet.php" class="back-button">Вернуться в кабинет</a>

    <script>
        $(document).ready(function () {
            // Обновление стоимости при изменении количества
            $('.amount').on('input', function () {
                const $row = $(this).closest('tr');
                const price = parseFloat($row.find('.price').data('price'));
                const quantity = parseInt($(this).val()) || 0;
                const totalPrice = price * quantity;

                // Обновление итоговой стоимости
                $row.find('.total-price').text(totalPrice.toFixed(2));

                // Обновление скрытого поля в форме
                $row.find('.ajax-amount').val(quantity);
            });

            // Инициализация стоимости при загрузке
            $('.amount').trigger('input');
        });
    </script>
</body>
</html>


