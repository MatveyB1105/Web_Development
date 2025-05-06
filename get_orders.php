<?php
    session_start();
	if (!isset($_COOKIE['client'])) {
        // Если cookie 'client' не существует, считаем, что сессия истекла
        $_SESSION['message'] = "Время сессии закончилось. Пожалуйста, авторизуйтесь повторно.";
        header('Location: auth_form.php');
        exit();
    } else {
        // Если cookie существует, продлеваем срок действия на 2 минуты (120 секунд)
        $lifetime = 120;
        $id = $_SESSION['client']['id'];
        setcookie('client', $id, time() + $lifetime, '/');
    }
?>

<!DOCTYPE html>
<html>
<head>
<style>
table {
  width: 100%;
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
  padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>
<?php
	$q = $_GET['text'];
	require_once("Connection/connection.php");
    $user_id = $_SESSION['client']['id'];
	$query = "select * from orders where CLient_id=".$user_id." AND (Order_ID='".$q."' OR status LIKE '%".$q."%' OR Creation_Date LIKE '".$q."' OR Perfomed_Date LIKE '".$q."')";
	$result = mysqli_query($conn, $query);
	echo "<table>
		<tr>
		<th>ID</th>
		<th>Дата создания</th>
		<th>Дата выполнения</th>
		<th>Статус</th>
		<th>Сотрудник</th>
		</tr>";
    while($row = mysqli_fetch_array($result)) {
          echo "<tr>";
          echo "<td>" .$row['Order_ID'] . "</td>";
          echo "<td>" . $row['Creation_Date'] . "</td>";
          echo "<td>" . $row['Perfomed_Date'] . "</td>";
          echo "<td>" . $row['status'] . "</td>";
          if(isset($row['Employee_ID'])){
            $sql = "SELECT first_name, Middle_Name, last_name from employee where Employee_ID=".$row['Employee_ID'];
            $res = mysqli_fetch_array(mysqli_query($conn, $sql));
            echo "<td>" . $res['first_name'] ." ".$res['Middle_Name']." ".$res['last_name']."</td>";
          }else {
            echo "<td></td>";
          };
          echo "</tr>";
    };
    echo "</table>";
//mysqli_close($con);
?>
</body>
</html>
