<?php
session_start();
require_once "db.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: catalog.php"); exit; }

// товар
$stmt = mysqli_prepare($conn, "SELECT id, name, short_description, description, price, image FROM product WHERE id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
if (!$product) { header("Location: catalog.php"); exit; }

// изображения
$images = [];
$q2 = mysqli_query($conn, "SELECT image FROM product_images WHERE product_id={$id} ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($q2)) { $images[] = $row['image']; }
if (!$images && !empty($product['image'])) { $images[] = $product['image']; } //fallback

// характеристики
$properties = [];
$q3 = mysqli_query($conn, "SELECT property_name, property_value FROM product_properties WHERE product_id={$id} ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($q3)) { $properties[] = $row; }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> — Стерх</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="alt">
<div class="wrapper">
    <?php include "header.php"; ?>

    <div class="container">
        <aside class="sidebar">
            <h3>Меню</h3>
            <ul>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="contacts.php">Контакты</a></li>
                <li><a href="guestbook.php">Гостевая</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2><?= htmlspecialchars($product['name']) ?></h2>

            <div class="product-wrap">
                <div>
                    <!-- СЛАЙДЕР -->
                    <div class="slider product-slider">
                        <div class="slides">
                            <?php foreach ($images as $img): ?>
                                <div class="slide"><img src="images/<?= htmlspecialchars($img) ?>" alt=""></div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($images) > 1): ?>
                        <div class="slider-buttons">
                            <button class="prev">&#10094;</button>
                            <button class="next">&#10095;</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="product-section-title">Краткое описание</div>
                    <p class="product-short"><?= nl2br(htmlspecialchars($product['short_description'])) ?></p>

                    <?php if ($properties): ?>
                        <div class="product-section-title">Характеристики</div>
                        <ul class="props-list bulleted">
                            <?php foreach ($properties as $p): ?>
                                <li><b><?= htmlspecialchars($p['property_name']) ?>:</b> <?= htmlspecialchars($p['property_value']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="product-section-title">Подробное описание</div>
                    <p class="product-long"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                    <p class="price" style="font-size:20px">Цена: <?= number_format($product['price'], 0, ',', ' ') ?> ₽</p>
                    <a class="cart-btn" href="cart.php?add=<?= (int)$product['id'] ?>">В корзину</a>
                </div>
            </div>
        </main>

        <aside class="image">
            <a href="https://shop.ctepx.ru/" target="_blank">
                <img src="images/banner.jpg" alt="Баннер">
            </a>
        </aside>
    </div>

    <?php include "footer.php"; ?>
</div>

<script src="scripts.js"></script>
</body>
</html>
