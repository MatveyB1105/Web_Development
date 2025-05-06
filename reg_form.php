<?php
 session_start();
 $_SESSION['page'] = 'reg';
?>

<!DOCTYPE html>
<html lang="en, ru">
  <head>
    <meta charset="UTF-8">
    <<title>Регистрация</title>
    <link rel="stylesheet" href="css/reg.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
	<div class="container">
		<div class="title">Регистрация</div>
    	<div class="content">
      		<form class="reg_main" action="reg.php" method="post">
        		<div class="user-details">
          			<div class="input-box">
            			<span class="details">Фамилия</span>
            			<input type="text" placeholder="Иванов" name="last_name" pattern="[А-Яа-яЁё]{,30}" required>
          			</div>
          			<div class="input-box">
            			<span class="details">Имя</span>
            			<input type="text" placeholder="Иван" name="first_name" pattern="[А-Яа-яЁё]{,30}" required>
          			</div>
		  			<div class="input-box">
						<span class="details">Отчество</span>
						<input type="text" placeholder="Иванович" name="patronymic" pattern="[А-Яа-яЁё]{,30}">
					</div>
					<div class="input-box">
						<span class="details">Дата рождения</span>
    					<input type="date" pattern="\d{4}.\d{1,2}.\d{1,2}" name="date_of_birth" required max="<?php echo date('Y-m-d') ?>">
					</div>
          			<div class="input-box">
            			<span class="details">Email</span>
            			<input type="email" name="email" placeholder="maymay@mail.ru" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
          			</div>
          			<div class="input-box">
            			<span class="details">Телефонный номер</span>
    				<input type="tel" name="phone_number" pattern="[+][7][9][0-9]{9}"  placeholder="+79XXXXXXXXX" required>
          			</div>
          			<div class="input-box">
            			<span class="details">Пароль</span>
            			<input type="password" placeholder="******" name="password" required>
          			</div>
          			<div class="input-box">
            			<span class="details">Подтвердите пароль</span>
            			<input type="password" placeholder="******" name="password_confirm" required>
          			</div>
        		</div>
				<div class="checkbox">
                    <label>
                        <input type="checkbox" name="is_employee"> Я сотрудник
                    </label>
                </div>
        		<div class="button">
          			<input type="submit" value="Зарегистрироваться">
        		</div>
      		</form>
    	</div>
  	</div>
	<?php
        if (!empty($_SESSION['message'])){
    	echo "<script>alert('".$_SESSION['message']."');</script>";
    	 unset($_SESSION['message']);
 	}
	?>
</body>
</html>
