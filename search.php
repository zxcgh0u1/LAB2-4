<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе
$conn = mysqli_connect('localhost', 'root', '', 'site');
mysqli_set_charset($conn, "utf8mb4");

$search_q = '';
$db_results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_q = trim($_POST['search_q'] ?? '');
    if ($search_q !== '') {
        $q = "%" . $search_q . "%";
        $sql = "SELECT id, name, short_description, image FROM product WHERE name LIKE ? OR short_description LIKE ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ss', $q, $q);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($res)) {
                $db_results[] = $row;
            }
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск — Салон штор «Стерх»</title>
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
                <li><a href="guestbook.php">Гостевая</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Поиск товара</h2>
            <form method="post" action="search.php" class="form-grid" style="max-width:420px;">
                <input type="search" name="search_q" placeholder="Введите часть названия или описания"
                       value="<?= htmlspecialchars($search_q) ?>"/>
                <input type="submit" value="Найти"/>
            </form>

            <?php if ($search_q !== ''): ?>
                <h3 style="margin-top:16px;">Результаты поиска:</h3>
                <?php if (!$db_results): ?>
                    <p>Ничего не найдено по запросу «<?= htmlspecialchars($search_q) ?>».</p>
                <?php else: ?>
                    <div class="catalog">
                        <?php foreach ($db_results as $row): ?>
                            <div class="item">
                                <img src="images/<?= htmlspecialchars($row['image']) ?>" width="150">
                                <p><a href="product.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></p>
                                <?= htmlspecialchars($row['short_description']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <aside class="image">
            <img src="images/main.jpg" alt="Фото" width="150">
        </aside>
    </div>

    <?php include "footer.php"; ?>
</div>
</body>
</html>
