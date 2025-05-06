<?php
session_start();
require_once "Connection/connection.php";

if (!isset($_SESSION['admin'])) {
    die("Доступ запрещен");
}

$employee_id = $_SESSION['admin']['id'];
$search = isset($_GET['text']) ? trim($_GET['text']) : '';

$query = "
    SELECT 
        orders.Order_ID,
        orders.Product_ID,
        orders.Creation_Date,
        orders.Perfomed_Date,
        orders.status,
        orders.amount,
        product.Name AS Product_Name,
        product.Price AS Product_Price
    FROM 
        orders
    JOIN 
        product ON orders.Product_ID = product.Product_ID
    WHERE 
        orders.Employee_ID = ?
";

if (!empty($search)) {
    $query .= " AND (
        orders.Order_ID LIKE ? 
        OR orders.status LIKE ? 
        OR orders.Creation_Date LIKE ? 
        OR orders.Perfomed_Date LIKE ?
    )";
}

$query .= " ORDER BY orders.Creation_Date DESC";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $searchParam = "%$search%";
    // Исправлено: "issss" вместо "isss"
    $stmt->bind_param("issss", $employee_id, $searchParam, $searchParam, $searchParam, $searchParam);
} else {
    $stmt->bind_param("i", $employee_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>'.$row['Order_ID'].'</td>';
        echo '<td>'.$row['Product_Name'].'</td>';
        echo '<td>'.$row['amount'].'</td>';
        echo '<td>'.$row['Creation_Date'].'</td>';
        echo '<td>'.($row['Perfomed_Date'] ?: 'Не выполнен').'</td>';
        echo '<td>'.$row['status'].'</td>';
        echo '<td>'.($row['Product_Price'] * $row['amount']).' руб.</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7">Заказы не найдены</td></tr>';
}
?>