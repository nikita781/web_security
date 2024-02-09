<?php
    $connect = mysqli_connect("localhost", "root", "", "test");
    error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ТЕСТ</title>
</head>
<body>
    <a href="auth.php">Авторизация</a>
    <form method="POST">
        <input type="text" placeholder="Логин" name="login"><br>
        <input type="password" placeholder="Пароль" name="password"><br>
        <input type="submit" name="add" value="Зарегистрироваться">
    </form>
</body>
</html>

<?php
    // $out = "SELECT * FROM `user`";
    // $out_run = mysqli_query($connect, $out);
    // $users = mysqli_fetch_array($out_run);

    $login = $_POST['login'];
    $password = $_POST['password'];
    $add = $_POST['add'];

    $str = "INSERT INTO `user` (`login`, `password`) VALUES ($login, $password)";

    if ($add) {
        $str_run = mysqli_query($connect, $str);
        if ($str_run) {
            header("Location:/dashboard.php");
        }
    }
?>