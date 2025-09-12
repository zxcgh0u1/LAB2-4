<?php
session_start();
require_once "db.php";

$perPage = 8;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$sort = $_GET['sort'] ?? '';
$allowed = [
    'price_asc'  => 'price ASC',
    'price_desc' => 'price DESC',
    'name_asc'   => 'name ASC',
    'name_desc'  => 'name DESC'
];
$orderBy = $allowed[$sort] ?? 'id DESC';

// всего
$totalRes = mysqli_query($conn, "SELECT COUNT(*) c FROM product");
$total = (int)mysqli_fetch_assoc($totalRes)['c'];
$pages = max(1, (int)ceil($total / $perPage));

// выборка
$stmt = mysqli_prepare($conn, "SELECT id, name, price, image FROM product ORDER BY $orderBy LIMIT ?,?");
mysqli_stmt_bind_param($stmt, 'ii', $offset, $perPage);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$items = [];
while ($row = mysqli_fetch_assoc($res)) { $items[] = $row; }
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог — Стерх</title>
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
                <li><a href="contacts.php">Контакты</a></li>
                <li><a href="guestbook.php">Гостевая</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2>Каталог</h2>

            <form method="get" style="margin-bottom:12px; display:flex; gap:10px; align-items:center;">
                <label>Сортировка:</label>
                <select name="sort" onchange="this.form.submit()">
                    <option value="">по умолчанию</option>
                    <option value="price_asc"  <?= $sort==='price_asc'?'selected':'' ?>>Цена ↑</option>
                    <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Цена ↓</option>
                    <option value="name_asc"   <?= $sort==='name_asc'?'selected':'' ?>>Название A–Я</option>
                    <option value="name_desc"  <?= $sort==='name_desc'?'selected':'' ?>>Название Я–A</option>
                </select>
            </form>

            <div class="catalog">
                <?php foreach ($items as $p): ?>
                    <div class="item">
                        <a href="product.php?id=<?= (int)$p['id'] ?>">
                            <img src="images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                        </a>
                        <a class="title" href="product.php?id=<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a>
                        <div class="price"><?= number_format($p['price'],0,',',' ') ?> ₽</div>
                        <a class="cart-btn" href="cart.php?add=<?= (int)$p['id'] ?>">В корзину</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($pages > 1): ?>
                <div class="pagination">
                    <?php for ($i=1; $i<=$pages; $i++): ?>
                        <?php if ($i==$page): ?>
                            <strong><?= $i ?></strong>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>&sort=<?= urlencode($sort) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>

        <aside class="image">
            <a href="https://shop.ctepx.ru/" target="_blank">
                <img src="images/banner.jpg" alt="Баннер">
            </a>
        </aside>
    </div>

    <?php include "footer.php"; ?>
</div>
</body>
</html>
