<?
include "bd.php";

$user = $_POST['username'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];

$nameFile = $_FILES['avatar']['name'];
$type = explode('/', $_FILES['avatar']['type']);
$tmp_path = $_FILES['avatar']['tmp_name'];
$size = $_FILES['avatar']['size'];

$rand = rand(200, 600);
$Ext = explode('.', $nameFile);
$nameFile = $Ext['0'];
$Ext = $Ext['1'];
$full_name = "$nameFile" . "_$rand" . ".$Ext";

$path = "image/$full_name";

$str_user = "INSERT INTO `users`(`name`, `email`, `password`, `phone`, `data`, `sex`, `avatar`) 
VALUES ('[$user','$email','$password','$phone','$dob','$gender','$full_name')";

$out_user_add = "SELECT * FROM `users` name";
$run_user_pro = mysqli_query($connect, $out_user_add);

if (empty($user)) {
    $_SESSION['error_name'] = "Заполните имя";
} else {
    while ($out_user = mysqli_fetch_array($run_user_pro)) {
        if ($user == $out_user['name']) {
            $_SESSION['error_name'] = "Такой пользователь уже есть";
        }
    }

    $i = strlen($user);
    while ($i--) {
        if (is_numeric($user[$i])) {
            $_SESSION['error_name'] = "Циферки нельзя";
            break;
        }
    }
}

if (strlen($password) < 8) {
    $_SESSION['error'] = "Пороль должен быть 8 символов или больше";
    // header("Location:/");
} else {


    if (preg_match("/(.)\\1\\1/", $password)) {
        $_SESSION['error'] = "У вас повторяющиеся символы)";
    }

    if (!preg_match("/[а-я]/i", $password) || !preg_match("/[a-z]/i", $password)) {
        $_SESSION['error'] =  'Вам нужно использовать и русские и английские символы';
    }
    if (strripos(strtolower($password), strtolower(date("M")))) {
        $_SESSION['error'] =  'Пароль должен сожержать название месяца на английском';
    }

}


$year = explode('-', $dob);

if ((int)date("Y") - (int)$year[0] >= 111) {
    $_SESSION['years'] = "Вам не может быть больше 111";
}

if (date("Y") < $year[0]) {
    $_SESSION['years'] = "Дата не может быть в будующем времени";
} elseif (date("Y") == $year[0] && date("m") < $year[1]) {
    $_SESSION['years'] = "Дата не может быть в будующем времени";
} elseif (date("Y") == $year[0] && date("m") == $year[1] && date("j") < $year[2]) {
    $_SESSION['years'] = "Дата не может быть в будующем времени";
}


if ($gender == "male") {
    if ((int)date("Y") - (int)$year[0] < 18) {
        $_SESSION['gender'] = "мальчик";
    } else {
        $_SESSION['gender'] = "мужчина";
    }
} elseif ($gender == "female") {
    if ((int)date("Y") - (int)$year[0] < 18) {
        $_SESSION['gender'] = "девочка";
    } else {
        $_SESSION['gender'] = "женщина";
    }
} else {
    $_SESSION['gender'] = "оно";
}

if ($size > 500000) {
    $_SESSION['file'] = "Файл слишком большой";
} elseif ($size < 200) {
    $_SESSION['file'] = "Файл слишком маленький";
}


if ($type[1] == "png" || $type[1] == "txt" || $type[1] == "jpg" || $type[1] == "jpeg") {
    move_uploaded_file($tmp_path, $path);
} else {
    $_SESSION['file'] = "Тип файла не подходит";
}

if ($_SESSION['file'] || $_SESSION['years'] || $_SESSION['error'] || $_SESSION['error_name']) {
    header("Location:/");
} else {
    header("Location:/");
    $run_add = mysqli_query($connect, $str_user);
}
