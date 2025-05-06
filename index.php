<?php
  session_start();
  if (isset($_COOKIE['client']) && isset($_SESSION['client']['id'])) {
    $lifetime = 120;
    $id = $_SESSION['client']['id'];

    if (!empty($id)) {
        setcookie('client', $id, time() + $lifetime, '/');
    }
}

?>

<!DOCTYPE html>
<html lang="ru, en">
<head>
    <meta name="viewport" content="with=device-width, initial-scale=1.0">
    <title>Brend For less</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
  	if (isset($_SESSION['message'])) {
      	echo "<script>alert('".$_SESSION['message']."');</script>";
     	unset($_SESSION['message']);
  	}
  	?>

    <section class="header">
        <nav>
            <div class="nav-links">
                <ul>
					<li><a href="auth_form.php">АВТОРИЗАЦИЯ</a></li>
	                <li><a href="reg_form.php">РЕГИСТРАЦИЯ</a></li>
                <ul>
            </div>
        </nav>

        <div class="text-box">
            <h1>Busy Market</h1>
            <p>Лучшие цены у нас</p>
            <a href="products_unauth" class="hero-btn">Посмотреть товары</a>
        </div>
    </section>
</body>
</html>