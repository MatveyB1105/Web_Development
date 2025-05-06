<?php
session_start();

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$patronymic = $_POST['patronymic'];
$email = $_POST['email'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Некорректный формат email.");
}

$phone_number = $_POST['phone_number'];
$date_of_birth = $_POST['date_of_birth'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$is_employee = isset($_POST['is_employee']) ? 1 : 0; // Проверяем, отмечена ли галочка

DEFINE('DB_USERNAME', 'root');
DEFINE('DB_PASSWORD', '');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_DATABASE', 'factory');

if ($password === $password_confirm) {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed<br>: " . $conn->connect_error);
    }
    mysqli_set_charset($conn, "utf8");

    if ($is_employee) {
        // Проверяем, есть ли сотрудник с таким email
        $check_user = $conn->query("SELECT * FROM employee WHERE Email = '$email'");
        $user = $check_user->fetch_assoc();
        if ($user != NULL) {
            $_SESSION['message'] = 'Сотрудник с таким email уже существует';
            header('Location: reg_form.php');
        } else {
            // Добавляем сотрудника
            if (!$conn->query("INSERT INTO employee (last_name, first_name, Middle_name, email, phone, date_of_birth, password) VALUES ('$last_name', '$first_name', '$patronymic', '$email', '$phone_number', '$date_of_birth', '$password')")) {
                $_SESSION['message'] = 'Ошибка при добавлении сотрудника. Проверьте правильность данных.';
                header('Location: reg_form.php');
            } else {
                $_SESSION['message'] = 'Вы успешно зарегистрировались как сотрудник.';
                header('Location: auth_form.php');
            }
        }
    } else {
        // Проверяем, есть ли клиент с таким email
        $check_user = $conn->query("SELECT * FROM client WHERE email = '$email'");
        $client = $check_user->fetch_assoc();
        if ($client != NULL) {
            $_SESSION['message'] = 'Клиент с таким email уже существует';
            header('Location: reg_form.php');
        } else {
            // Добавляем клиента
            if (!$conn->query("INSERT INTO client (last_name, first_name, Middle_name, phone, email, date_of_birth, password) VALUES ('$last_name', '$first_name', '$patronymic', '$phone_number', '$email', '$date_of_birth', '$password')")) {
                $_SESSION['message'] = 'Ошибка при добавлении клиента. Проверьте правильность данных.';
                header('Location: reg_form.php');
            } else {
                $_SESSION['message'] = 'Вы успешно зарегистрировались как клиент.';
                header('Location: auth_form.php');
            }
        }
    }
    $conn->close();
} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: reg_form.php');
}
?>
