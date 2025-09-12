<?php
session_start();
include "db.php";

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($username !== '' && $email !== '' && $message !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO messages (username, email, message) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $message);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $success = "Сообщение успешно отправлено!";
    } else {
        $error = "Заполните все поля!";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Контакты — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">

    <?php include "header.php"; ?>

    <div class="container">
        <aside class="sidebar">
            <h3>Меню</h3>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="guestbook.php">Гостевая</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Контакты</h2>

            <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="post" class="form-grid" style="max-width:420px;">
                <input type="text" name="username" placeholder="Ваше имя">
                <input type="email" name="email" placeholder="Email">
                <textarea name="message" placeholder="Ваше сообщение"></textarea>
                <button type="submit">Отправить</button>
            </form>

            <h3 id="address" style="margin-top:20px;">Наш адрес</h3>
            <p>308013, РФ, Белгородская область, Белгород, ул. Макаренко, 5а</p>

            <h3 id="map" style="margin-top:20px;">Карта</h3>
            <iframe src="https://yandex.ru/map-widget/v1/?ll=36.594079%2C50.602639&z=17&text=Россия%2C%20Белгород%2C%20улица%20Макаренко%2C%205а"
                    width="100%" height="400" frameborder="0"></iframe>
        </main>

        <aside class="image">
    <a href="https://shop.ctepx.ru/" target="_blank">
        <img src="images/banner.jpg" alt="Баннер" style="max-width:100%;">
    </a>
</aside>


    </div>

    <?php include "footer.php"; ?>
</div>
</body>
</html>
