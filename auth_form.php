<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="ru,en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/auth.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="container">
      <form class="auth_main" action="auth.php" method="get">
        <div class="title">Вход</div>
        <div class="input-box underline">
          <input type="text" name="email" placeholder="Введите почту" required>
          <div class="underline"></div>
        </div>
        <div class="input-box">
          <input type="password" name="password" placeholder="Введите пароль" required>
          <div class="underline"></div>
        </div>
        <div class="check-box">
          <input type="checkbox" id="checkbox" name="isAdmin" value="yes">
          <label for="checkbox"> Сотрудник </label>
        </div>
        <div class="input-box button">
          <input type="submit" name="" value="Войти">
        </div>
        <?php
			if (isset($_SESSION['message'])) {
            	echo "<script>alert('".$_SESSION['message']."');</script>";
            	unset($_SESSION['message']);
        	}
		?>
      </form>
        <div class="option">Нет аккаунта? <a href="reg_form.php">Зарегистрироваться</a></div>
    </div>
  </body>
</html>