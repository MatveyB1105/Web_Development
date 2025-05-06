<?php
session_start();

require_once "Connection/connection.php";

// Проверяем, есть ли cookie для клиента
if (!isset($_COOKIE['client'])) {
    $_SESSION['message'] = "Время сессии закончилось. Пожалуйста, авторизуйтесь повторно.";
    header('Location: auth_form.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $client_id = $_SESSION['client']['id'];
    $amount = intval($_POST['amount']);

    if ($amount <= 0) {
        $_SESSION['message'] = "Количество должно быть больше 0.";
        header("Location: create_order.php");
        exit();
    }

    $conn->begin_transaction();

    try {
        // Блокируем продукт для чтения другими
        $query = "SELECT Total_Quantity, Price FROM Product WHERE Product_ID = ? FOR UPDATE";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) {
            throw new Exception("Продукт не найден.");
        }

        if ($product['Total_Quantity'] < $amount) {
            $conn->rollback();
            $_SESSION['message'] = "Недостаточно товара на складе.";
            header("Location: create_order.php");
            exit();
        }

        // Рассчитываем стоимость заказа
        $order_total = $product['Price'] * $amount;

        // Вставляем заказ
        $creation_date = date('Y-m-d');
        $insert_order = "INSERT INTO `Orders` (Client_id, Product_ID, Creation_Date, amount, status) VALUES (?, ?, ?, ?, 'Создан')";
        $stmt = $conn->prepare($insert_order);
        $stmt->bind_param("iisi", $client_id, $product_id, $creation_date, $amount);

        if (!$stmt->execute()) {
            throw new Exception("Ошибка добавления заказа: " . $stmt->error);
        }

        // Обновляем количество на складе
        $update_product = "UPDATE Product SET Total_Quantity = Total_Quantity - ? WHERE Product_ID = ?";
        $stmt = $conn->prepare($update_product);
        $stmt->bind_param("ii", $amount, $product_id);

        if (!$stmt->execute()) {
            throw new Exception("Ошибка обновления товара: " . $stmt->error);
        }

        // Фиксируем транзакцию
        $conn->commit();

        // Передаем информацию о заказе для вывода
        $_SESSION['order_message'] = "Ваш заказ успешно создан! 
            ID продукта: $product_id, Количество: $amount, Общая стоимость: $order_total руб.";
        header("Location: create_order.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Ошибка: " . $e->getMessage();
        header("Location: create_order.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Неверный запрос.";
    header("Location: create_order.php");
    exit();
}
?>
