<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<head>
    <meta charset="UTF-8">
    <title>Салон штор «Стерх»</title>
    <link rel="stylesheet" href="/style.css">
</head>

<div class="top-line"></div>

<div class="header">
    <div class="logo-block">
        <img src="images/logo.jpg" alt="Логотип" class="logo-img">
        <div class="site-title">Салон штор «Стерх»</div>
    </div>

    <div class="login-form">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Привет, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</p>
            <a href="profile.php" class="btn">Личный кабинет</a>
            <a href="cart.php" class="btn">Моя корзина</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_products.php" class="btn">Админка</a>
                <a href="admin_messages.php" class="btn">Сообщения</a>
            <?php endif; ?>
            <a href="logout.php" class="btn">Выход</a>
        <?php else: ?>
            <a href="login.php" class="btn">Вход</a>
            <a href="register.php" class="btn">Регистрация</a>
        <?php endif; ?>
    </div>
</div>

<nav class="top-menu">
    <a href="index.php">Главная</a>
    <a href="catalog.php">Каталог</a>
    <a href="contacts.php">Контакты</a>
    <a href="guestbook.php">Гостевая</a>

    <form method="post" action="search.php" class="search-form">
        <input type="search" name="search_q" placeholder="Поиск по товарам">
        <button type="submit" class="search-btn">🔍</button>
    </form>
</nav>
