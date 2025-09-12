<?php
session_start();
include "db.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">
    <?php include "header.php"; ?>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="catalog.php">Каталог</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Вход</h2>
            <form class="form-grid" method="post" action="">
                <input type="text" name="login" placeholder="Логин или email">
                <input type="password" name="password" placeholder="Пароль">
                <button type="submit">Войти</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $l = trim($_POST['login']);
                $p = $_POST['password'];

                $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$l' OR email='$l'");
                if ($row = mysqli_fetch_assoc($res)) {
                    if (password_verify($p, $row['password'])) {
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['role'] = $row['role']; // ✅ добавили роль
                        header("Location: profile.php");
                        exit;
                    } else {
                        echo '<p style="color:#a00;">Неверный пароль.</p>';
                    }
                } else {
                    echo '<p style="color:#a00;">Пользователь не найден.</p>';
                }
            }
            ?>
        </main>
    </div>
    <?php include "footer.php"; ?>
</div>
</body>
</html>
