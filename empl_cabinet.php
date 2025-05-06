<?php
	session_start();

    require_once "Connection/connection.php";

	$empl_id = $_SESSION['admin']['id'];
    $name_query = "SELECT first_name FROM employee WHERE employee_id=$empl_id";
    $name = (($conn -> query($name_query)) -> fetch_assoc())['first_name'];

    if(isset($_SESSION['message'])){
        echo "<script>alert('".$_SESSION['message']."')</script>";
        unset($_SESSION['message']);
    }




?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/cabinet1.css">
</head>
<body>
    <header class="cabinet-header">
        <div class="container" style="color: black;">
			<?php
                echo '<h1>Здравствуйте, '.$name.'!</h1>';
            ?>
            <p>Выберите действие ниже:</p>
        </div>
    </header>
    <main class="cabinet-actions">
        <div class="container">
            <div class="card">
                <h2>Свободные заказы</h2>
                <p>Откройте каталог заказов.</p>
                <form action="free_orders.php" method="POST">
                    <button class="btn" type="submit" name="show_products">Посмотреть свободные</button>
                </form>
            </div>
            <div class="card">
                <h2>Изменить статус заказов</h2>
                <p>Измените статус своих заказов.</p>
                <form action="update_orders.php" method="POST">
                    <button class="btn" type="submit" name="create_order">Изменить заказ</button>
                </form>
            </div>
            <div class="card">
                <h2>Мои заказы</h2>
                <p>Просмотрите историю ваших заказов.</p>
                <form action="empl_orders.php" method="POST">
                    <button class="btn" type="submit" name="user_orders">Мои заказы</button>
                </form>
            </div>
            <div class="card">
                <h2>Главная страница</h2>
                <p>Вернитесь на главную страницу сайта.</p>
                <form action="index.php" method="POST">
                    <button class="btn" type="submit">На главную</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
