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
    $add = $_POST['add'];

    $out = "SELECT * FROM `user` WHERE `login`=$login AND `password`=$password";
    $out_admin = "SELECT * FROM `user` WHERE `login`=$login AND `password`=$password AND `admin`='1'";

    if ($add) {
        $out_run = mysqli_query($connect, $out_admin);
        $users = mysqli_num_rows($out_run);
        if ($users != 0) {
            $_SESSION['admin'] = 1;
            header("Location:/admin.php");
        } else {
            $run = mysqli_query($connect, $out);
            $user = mysqli_num_rows($run);
            if ($user != 0) {
                header("Location:/dashboard.php");
            }
        }
    }
?>