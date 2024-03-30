<?include "bd.php";?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Форма регистрации пользователя</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"],
    .form-group input[type="date"],
    .form-group input[type="file"] {
      width: calc(100% - 10px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    .form-group input[type="radio"] {
      margin-right: 5px;
    }

    .form-group .gender-label {
      margin-right: 15px;
    }

    .form-group input[type="file"] {
      margin-top: 5px;
    }

    .form-group button {
      padding: 10px 20px;
      background-color: #007bff;
      border: none;
      color: #fff;
      border-radius: 3px;
      cursor: pointer;
    }

    .form-group button:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: red;
      margin-top: 5px;
    }
  </style>
</head>

<body>

  <div class="container">
    <h2>Форма регистрации пользователя</h2>
    <form id="registrationForm" method="post" enctype="multipart/form-data" action="controller.php">
      <div class="form-group">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <div id="usernameError" class="error-message"><?echo $_SESSION['error_name'];?></div>
      </div>
      <div class="form-group">
        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <div id="passwordError" class="error-message"><?echo $_SESSION['error'];?></div>
      </div>
      <div class="form-group">
        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" required>
      </div>
      <div class="form-group">
        <label for="dob">Дата рождения:</label>
        <input type="date" id="dob" name="dob" required>
        <div id="dobError" class="error-message"><?echo $_SESSION['years'];?></div>
      </div>
      <div class="form-group">
        <label for="gender" class="gender-label">Пол:</label>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">М</label>
        <input type="radio" id="female" name="gender" value="female" required>
        <label for="female">Ж</label>
        <div id="genderError" class="error-message"><?echo $_SESSION['gender'];?></div>
      </div>
      <div class="form-group">
        <label for="avatar">Картинки:</label>
        <input type="file" id="avatar" name="avatar" accept="image/*" required multiple>
        <div id="avatarError" class="error-message"><?echo $_SESSION['file'];?></div>
      </div>
      <div class="form-group">
        <input type="submit" name="send" value="Отправить">
      </div>
    </form>
  </div>

  <script>
$(document).ready(function() {
    // Валидация изображений
  $('#avatar').on('change', function() {
    var files = $(this)[0].files;
    var avatarError = $('#avatarError');
    avatarError.html("");

    // Проверка количества выбранных файлов
    if (files.length === 0) {
      avatarError.html("Выберите хотя бы одно изображение");
      return;
    }

    // Проверка расширений файлов и их размера
    var maxSize = 5 * 1024 * 1024; // Максимальный размер файла: 5 MB
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var fileSize = file.size;
      var fileName = file.name;
      var fileExt = fileName.split('.').pop().toLowerCase(); // Получаем расширение файла
      var allowedExt = ['jpg', 'jpeg', 'png', 'gif']; // Разрешенные расширения файлов

      if (allowedExt.indexOf(fileExt) === -1) {
        avatarError.html("Недопустимый формат файла. Поддерживаемые форматы: " + allowedExt.join(", "));
        return;
      }

      if (fileSize > maxSize) {
        avatarError.html("Превышен максимальный размер файла (5 MB)");
        return;
      }
    }
  });

  // Применение маски для телефонного номера
  $('#phone').inputmask('+7 (999) 999-99-99');

  // Валидация даты рождения
  $('#dob').on('input', function() {
    var dob = $(this).val();
    var dobError = $('#dobError');
    dobError.html("");

    // Проверка формата даты
    if (!isValidDate(dob)) {
      dobError.html("Неверный формат даты рождения");
      return;
    }

    // Проверка будущих дат
    if (new Date(dob) > new Date()) {
      dobError.html("Дата рождения не может быть в будущем");
      return;
    }

    // Проверка возраста
    var today = new Date();
    var birthDate = new Date(dob);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    if (age > 111) {
      dobError.html("Дата рождения не должна превышать 111 лет");
      return;
    }
  });

  // Валидация имени пользователя
  $('#username').on('input', function() {
    var username = $(this).val();
    var usernameError = $('#usernameError');
    usernameError.html("");

    // Проверка наличия цифр
    if (/\d/.test(username)) {
      usernameError.html("Имя пользователя не должно содержать цифры");
      return;
    }
  });

  // Функция для проверки формата даты
  function isValidDate(dateString) {
    var regEx = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateString.match(regEx)) return false;  // Неправильный формат
    var d = new Date(dateString);
    var dNum = d.getTime();
    if (!dNum && dNum !== 0) return false; // Неправильная дата
    return d.toISOString().slice(0, 10) === dateString;
  }
});

document.getElementById("registrationForm").addEventListener("submit", function(event) {
  var password = document.getElementById("password").value;

  var errorElement = document.getElementById("passwordError");
  errorElement.innerHTML = "";

  // Проверка длины пароля
  if (password.length < 8) {
    errorElement.innerHTML = "Пароль должен содержать не менее 8 символов";
    event.preventDefault();
    return;
  }

  // Проверка на повторяющиеся символы
  if (/(\w)\1{2,}/.test(password)) {
    errorElement.innerHTML = "Пароль не должен содержать повторяющиеся символы";
    event.preventDefault();
    return;
  }

  // Проверка на повторяющиеся комбинации
  if (/(\w{2,}).*?\1/.test(password)) {
    errorElement.innerHTML = "Пароль не должен содержать повторяющиеся комбинации";
    event.preventDefault();
    return;
  }

  // Проверка на наличие текущего месяца в пароле
  var monthNames = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
  var currentMonth = monthNames[new Date().getMonth()];
  if (password.toLowerCase().indexOf(currentMonth) === -1) {
    errorElement.innerHTML = "Пароль должен содержать название текущего месяца";
    event.preventDefault();
    return;
  }

  // Проверка на наличие не менее 2х спец символов, не идущих подряд
  if (!/^(?=.*[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?])(?=(.*[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]){2})/.test(password)) {
    errorElement.innerHTML = "Пароль должен содержать не менее 2х спец символов, не идущих подряд";
    event.preventDefault();
    return;
  }
});
</script>

</body>

</html>
<?
unset($_SESSION['error']);
unset($_SESSION['file']);
unset($_SESSION['gender']);
unset($_SESSION['years']);
unset($_SESSION['error_name']);
?>