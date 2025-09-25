<?php
session_start();
require_once "db.php";

$msgOk = $msgErr = "";

// Добавление записи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $message  = trim($_POST['message'] ?? '');
    $rating   = (int)($_POST['rating'] ?? 5);
    $recommend= !empty($_POST['recommend']) ? 1 : 0;
    $product_ref = trim($_POST['product_ref'] ?? '');

    if ($username !== '' && $message !== '') {
        // Если товар не выбран
        if ($product_ref === '') {
            $product_ref = null;
        }

        // Вставка в БД (добавили rating!)
        if ($stmt = mysqli_prepare($conn, "INSERT INTO guestbook (username, message, product_ref, rating) VALUES (?, ?, ?, ?)")) {
            mysqli_stmt_bind_param($stmt, 'ssii', $username, $message, $product_ref, $rating);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

       

        $msgOk = "Спасибо! Сообщение добавлено.";
    } else {
        $msgErr = "Заполните имя и отзыв.";
    }
}

// Получаем все записи с JOIN для товара
$res = mysqli_query($conn, "SELECT g.*, p.name AS product_name
                            FROM guestbook g
                            LEFT JOIN product p ON g.product_ref = p.id
                            ORDER BY g.created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Гостевая — Стерх</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stars {
            color: #ff9900;
            font-size: 18px;
        }
    </style>
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
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Гостевая книга</h2>

            <?php if ($msgOk): ?><p style="color:green;"><?= htmlspecialchars($msgOk) ?></p><?php endif; ?>
            <?php if ($msgErr): ?><p style="color:red;"><?= htmlspecialchars($msgErr) ?></p><?php endif; ?>

            <form class="form-grid" method="post" action="guestbook.php" style="max-width:520px;">
                <input type="text" name="username" placeholder="Ваше имя" required>

                <!-- Радио (оценка) -->
                <div>Оценка:
                    <label><input type="radio" name="rating" value="5" checked> 5</label>
                    <label><input type="radio" name="rating" value="4"> 4</label>
                    <label><input type="radio" name="rating" value="3"> 3</label>
                    <label><input type="radio" name="rating" value="2"> 2</label>
                    <label><input type="radio" name="rating" value="1"> 1</label>
                </div>

                <!-- Чекбокс -->
                <label><input type="checkbox" name="recommend" value="1"> Рекомендую</label>

                <!-- Выпадающий список -->
                <select name="product_ref">
                    <option value="">Товар (необязательно)</option>
                    <?php
                    $resProd = mysqli_query($conn, "SELECT id, name FROM product ORDER BY name ASC");
                    while ($rowP = mysqli_fetch_assoc($resProd)): ?>
                        <option value="<?= (int)$rowP['id'] ?>"><?= htmlspecialchars($rowP['name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <!-- Отзыв -->
                <textarea name="message" placeholder="Ваш отзыв" style="max-height:220px; overflow:auto;" required></textarea>

                <button type="submit">Отправить</button>
            </form>

            <h3 style="margin-top:16px;">Отзывы</h3>
            <?php if ($res && mysqli_num_rows($res)>0): while ($row = mysqli_fetch_assoc($res)): ?>
                <div style="border-bottom:1px solid #ddd; margin:10px 0; padding:10px 0;">
                    <p><b><?= htmlspecialchars($row['username']) ?></b>
                    <span style="color:#888;">(<?= htmlspecialchars($row['created_at']) ?>)</span></p>

                    <!-- Текст отзыва -->
                    <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

                    <!-- Оценка -->
                    <p class="stars">
                        <?php
                        $stars = (int)$row['rating'];
                        echo str_repeat("⭐", $stars) . str_repeat("☆", 5 - $stars);
                        ?>
                    </p>

                    <!-- Товар -->
                    <?php if ($row['product_name']): ?>
                        <p><em>Товар: <?= htmlspecialchars($row['product_name']) ?></em></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; else: ?>
                <p>Пока нет отзывов.</p>
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
