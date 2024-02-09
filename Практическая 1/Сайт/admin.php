<?php 
    session_start();
    if (!$_SESSION['admin']) {
        header("Location:/index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ТЕСТ</title>
</head>
<body>
    Страница админа<br>
    <a href="dashboard.php">Страница пользователя</a>
</body>
</html>