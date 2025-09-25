<?php
session_start();
require_once "db.php";

$msgOk = $msgErr = "";

// Добавление записи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $message  = trim($_POST['message'] ?? '');
    $rating   = trim($_POST['rating'] ?? '5');
    $recommend= !empty($_POST['recommend']) ? 1 : 0;
    $product_ref = trim($_POST['product_ref'] ?? '');

    if ($username !== '' && $message !== '') {
        // В БД — только как было (username, message)
        if ($stmt = mysqli_prepare($conn, "INSERT INTO guestbook (username, message) VALUES (?, ?)")) {
            mysqli_stmt_bind_param($stmt, 'ss', $username, $message);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // В файл — расширенные данные (для зачёта Л2 «работа с файлами»)
        $log = date('Y-m-d H:i:s') . " | {$username} | rating={$rating} | rec={$recommend} | {$product_ref}\n"
             . $message . "\n---\n";
        @file_put_contents(__DIR__.'/guestbook.txt', $log, FILE_APPEND | LOCK_EX);

        $msgOk = "Спасибо! Сообщение добавлено.";
    } else {
        $msgErr = "Заполните имя и отзыв.";
    }
}

// Получаем все записи (как были)
$res = mysqli_query($conn, "SELECT * FROM guestbook ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Гостевая — Стерх</title>
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
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Гостевая книга</h2>

            <?php if ($msgOk): ?><p style="color:green;"><?= htmlspecialchars($msgOk) ?></p><?php endif; ?>
            <?php if ($msgErr): ?><p style="color:red;"><?= htmlspecialchars($msgErr) ?></p><?php endif; ?>

            <form class="form-grid" method="post" action="guestbook.php" style="max-width:520px;">
                <input type="text" name="username" placeholder="Ваше имя" required>

                <!-- Радио (переключатель оценки) -->
                <div>Оценка:
                    <label><input type="radio" name="rating" value="5" checked> 5</label>
                    <label><input type="radio" name="rating" value="4"> 4</label>
                    <label><input type="radio" name="rating" value="3"> 3</label>
                </div>

                <!-- Чекбокс -->
                <label><input type="checkbox" name="recommend" value="1"> Рекомендую</label>

                <!-- Выпадающий список -->
                <select name="product_ref">
                    <option value="">Товар (необязательно)</option>
                    <option>Шторы классические</option>
                    <option>Тюль ажурный</option>
                    <option>Шторы велюровые</option>
                    <option>Тюль с узором</option>
                    <option>Портьеры blackout</option>
                </select>

                <!-- Прокручиваемое многострочное поле -->
                <textarea name="message" placeholder="Ваш отзыв" style="max-height:220px; overflow:auto;" required></textarea>

                <button type="submit">Отправить</button>
            </form>

            <h3 style="margin-top:16px;">Отзывы</h3>
            <?php if ($res && mysqli_num_rows($res)>0): while ($row = mysqli_fetch_assoc($res)): ?>
                <div style="border-bottom:1px solid #ddd; margin:10px 0; padding:10px 0;">
                    <p><b><?= htmlspecialchars($row['username']) ?></b>
                    <span style="color:#888;">(<?= htmlspecialchars($row['created_at']) ?>)</span></p>
                    <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
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
