<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">

    <?php include "header.php"; ?>
<main>
    <div class="container">
        <aside class="sidebar">
            <h3>Меню</h3>
            <ul>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="contacts.php">Контакты</a></li>
                <li><a href="guestbook.php">Гостевая</a></li>
            </ul>
        </aside>

        <div class="content">
            <h2>Добро пожаловать в Салон штор «Стерх»</h2>

            <!-- === СЛАЙДЕР БАННЕРОВ === -->
            <div class="slider banner-slider">
                <div class="slides">
                    <div class="slide">
                         <a href="catalog.php">
                            <img src="images/banner1.jpg" alt="Баннер 1">
                        </a>
                    </div>
                    <div class="slide">
                         <a href="catalog.php">
                            <img src="images/banner2.jpg" alt="Баннер 2">
                        </a>
                    </div>
                    <div class="slide">
                         <a href="catalog.php">
                            <img src="images/banner3.jpg" alt="Баннер 3">
                        </a>
                    </div>
                </div>
                <div class="slider-buttons">
                    <button class="prev">&#10094;</button>
                    <button class="next">&#10095;</button>
                </div>
            </div>

            <p>Мы предлагаем широкий выбор штор, тюлей и портьер на любой вкус.</p>
        </div>

        <aside class="image">
            <a href="https://www.cian.ru/" target="_blank">
                <img src="images/banner.jpg" alt="Баннер" style="max-width:100%;">
            </a>
            <a href="https://www.tbank.ru/" target="_blank">
        <img src="images/banner_1.jpg" alt="Баннер 2" style="margin-top:15px;">
    </a>
        </aside>
    </div>
</main>

    <?php include "footer.php"; ?>
</div>

<script src="scripts.js"></script>
</body>
</html>
