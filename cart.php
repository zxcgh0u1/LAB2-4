<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = (int)$_SESSION['user_id'];

/* Добавление товара */
if (isset($_GET['add'])) {
    $pid = (int)$_GET['add'];
    $check = mysqli_query($conn, "SELECT id FROM cart WHERE user_id={$user_id} AND product_id={$pid}");
    if ($check && mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id={$user_id} AND product_id={$pid}");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ({$user_id}, {$pid}, 1)");
    }
    header("Location: cart.php");
    exit;
}

/* Удаление товара */
if (isset($_GET['delete'])) {
    $cid = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE id={$cid} AND user_id={$user_id}");
    header("Location: cart.php");
    exit;
}

/* Получаем содержимое корзины */
$res = mysqli_query(
    $conn,
    "SELECT c.id   AS cid,
            c.quantity,
            p.id   AS pid,
            p.name,
            p.price,
            p.image
     FROM cart c
     JOIN product p ON p.id = c.product_id
     WHERE c.user_id = {$user_id}
     ORDER BY c.id DESC"
);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Моя корзина — Салон штор «Стерх»</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="alt">
<div class="wrapper">

    <?php include "header.php"; ?>

    <div class="container">
        <aside class="sidebar">
            <h3>Меню</h3>
            <ul>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="profile.php">Личный кабинет</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Ваша корзина</h2>

            <?php if ($res && mysqli_num_rows($res) > 0): ?>
                <?php 
                $total = 0;
                while ($row = mysqli_fetch_assoc($res)):
                    $sum = $row['price'] * $row['quantity'];
                    $total += $sum;
                ?>
                    <div class="cart-item">
                        <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="cart-item-info">
                            <div class="cart-item-title"><?= htmlspecialchars($row['name']) ?></div>
                            <div class="cart-item-price">
                                Цена: <?= number_format($row['price'], 0, ',', ' ') ?> ₽ × <?= (int)$row['quantity'] ?>
                                = <b><?= number_format($sum, 0, ',', ' ') ?> ₽</b>
                            </div>
                        </div>
                        <a href="cart.php?delete=<?= (int)$row['cid'] ?>" class="cart-btn"
                           onclick="return confirm('Удалить товар из корзины?')">Удалить</a>
                    </div>
                <?php endwhile; ?>

                <div class="cart-summary">
                    <p><b>Итого: <?= number_format($total, 0, ',', ' ') ?> ₽</b></p>
                    <a href="checkout.php" class="cart-btn">Оформить заказ</a>
                </div>
            <?php else: ?>
                <div class="cart-empty">
                    Ваша корзина пуста.<br>
                    <a href="catalog.php">Перейти в каталог</a>
                </div>
            <?php endif; ?>
        </main>

        <aside class="image">
            <a href="https://www.cian.ru/" target="_blank">
                <img src="images/banner.jpg" alt="Баннер" style="max-width:100%;">
            </a>
            <a href="https://www.tbank.ru/" target="_blank">
        <img src="images/banner_1.jpg" alt="Баннер 2" style="margin-top:15px;">
    </a>
        </aside>
    </div>

    <?php include "footer.php"; ?>
</div>
</body>
</html>
