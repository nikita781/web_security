<?php
    $connect = mysqli_connect("localhost", "root", "", "test");
    error_reporting(0);
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ТЕСТ</title>
</head>
<body>
    <form method="POST">
        <input type="text" placeholder="Логин" name="login"><br>
        <input type="password" placeholder="Пароль" name="password"><br>
        <input type="submit" name="add" value="Войти">
    </form>
</body>
</html>

<?php
    $login = $_POST['login'];
    $password = $_POST['password'];
    $button = $_POST['add'];

    $stmt = $connect->prepare("SELECT * FROM user where login = ? AND password = ?");
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location:/dashboard.php");
    }
?>
