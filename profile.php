<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">
    <?php include "header.php"; ?>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="logout.php">Выход</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Личный кабинет</h2>
            <p>Привет, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</p>
            <p>Здесь можно будет добавить: историю заказов, редактирование профиля и т.п.</p>
        </main>
    </div>
    <?php include "footer.php"; ?>
</div>
</body>
</html>
