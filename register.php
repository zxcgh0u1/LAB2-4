<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'site');
mysqli_set_charset($conn, "utf8mb4");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">
    <?php include "header.php"; ?>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="login.php">Вход</a></li>
                <li><a href="catalog.php">Каталог</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Регистрация</h2>
            <form class="form-grid" method="post" action="">
                <input type="text" name="username" placeholder="Имя пользователя">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Пароль">
                <input type="password" name="password2" placeholder="Повторите пароль">
                <button type="submit">Зарегистрироваться</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $u = trim($_POST['username']);
                $e = trim($_POST['email']);
                $p1 = $_POST['password'];
                $p2 = $_POST['password2'];

                $errors = [];
                if ($u === '' || $e === '' || $p1 === '' || $p2 === '') $errors[] = 'Заполните все поля.';
                if ($p1 !== $p2) $errors[] = 'Пароли не совпадают.';

                // Проверка уникальности
                $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$u' OR email='$e'");
                if (mysqli_num_rows($check) > 0) $errors[] = 'Такой пользователь уже есть.';

                if ($errors) {
                    echo '<ul style="color:#a00;">';
                    foreach ($errors as $er) echo '<li>'.htmlspecialchars($er).'</li>';
                    echo '</ul>';
                } else {
                    $hash = password_hash($p1, PASSWORD_DEFAULT);
                    mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$u','$e','$hash')");
                    echo '<p style="color:green;">Регистрация прошла успешно. Теперь <a href="login.php">войдите</a>.</p>';
                }
            }
            ?>
        </main>
    </div>
    <?php include "footer.php"; ?>
</div>
</body>
</html>
