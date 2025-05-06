<?php
session_start();
require_once "Connection/connection.php";

// Проверяем, авторизован ли сотрудник
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Пожалуйста, авторизуйтесь.";
    header('Location: auth_form.php'); // Страница авторизации сотрудника
    exit();
}

// Проверяем, был ли отправлен идентификатор заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $employee_id = $_SESSION['admin']['id'];

    $conn->begin_transaction(); // Начинаем транзакцию

    try {
        // Блокируем запись о заказе для предотвращения одновременной обработки
        $query = "SELECT Order_ID, Employee_ID FROM orders WHERE Order_ID = ? FOR UPDATE";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        sleep(3);
        // Проверяем, существует ли заказ
        if (!$order) {
            throw new Exception("Заказ не найден.");
        }
        // Проверяем, не взят ли заказ уже другим сотрудником
        if ($order['Employee_ID'] !== null) {
            throw new Exception("Заказ уже обработан другим сотрудником.");
        }

        // Обновляем заказ, связывая его с текущим сотрудником
        $update_query = "UPDATE orders SET Employee_ID = ?, status = 'В работе' WHERE Order_ID = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $employee_id, $order_id);

        if (!$stmt->execute()) {
            throw new Exception("Не удалось обновить заказ.");
        }

        // Фиксируем транзакцию
        $conn->commit();

        $_SESSION['message'] = "Вы успешно взяли заказ с ID $order_id.";
        header('Location: free_orders.php'); // Возвращаемся к списку свободных заказов
        exit();
    } catch (Exception $e) {
        // Откатываем транзакцию в случае ошибки
        $conn->rollback();
        $_SESSION['message'] = "Ошибка: " . $e->getMessage();
        header('Location: free_orders.php');
        exit();
    }
} else {
    $_SESSION['message'] = "Неверный запрос.";
    header('Location: free_orders.php');
    exit();
}
?>

