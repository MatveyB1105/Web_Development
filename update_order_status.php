<?php
session_start();
require_once "Connection/connection.php";

// Проверяем, авторизован ли сотрудник
if (!isset($_SESSION['admin'])) {
    $_SESSION['message'] = "Пожалуйста, авторизуйтесь.";
    header('Location: auth_form.php');
    exit();
}

// Получаем данные из формы
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $employee_id = $_SESSION['admin']['id'];

    // Проверяем, принадлежит ли заказ текущему сотруднику
    $query_check = "SELECT * FROM orders WHERE Order_ID = ? AND Employee_ID = ?";
    $stmt = $conn->prepare($query_check);
    $stmt->bind_param("ii", $order_id, $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['message'] = "У вас нет прав на изменение этого заказа.";
        header('Location: update_orders.php'); // Страница управления заказами
        exit();
    }

    // Обновляем статус заказа
    $query_update = "UPDATE orders SET status = 'Завершен', Perfomed_Date = NOW() WHERE Order_ID = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("i", $order_id);
    $stmt_update->execute();

    $_SESSION['message'] = "Статус заказа успешно обновлён.";
    header('Location: update_orders.php');
    exit();
} else {
    $_SESSION['message'] = "Некорректный запрос.";
    header('Location: update_orders.php');
    exit();
}
?>
<form action="empl_cabinet.php" method="get">
    <button type="submit">Вернуться в кабинет</button>
</form>
