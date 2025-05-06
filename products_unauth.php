<?php
require_once "Connection/connection.php";
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
    <a href="index.php" class="back-button">Вернуться на главную страницу</a>
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