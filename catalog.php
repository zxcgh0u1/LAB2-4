<?php
session_start();
require_once "db.php";

// сколько товаров на страницу
$perPage = 8;

// сортировка
$sort = $_GET['sort'] ?? '';
$allowed = [
    'price_asc'  => 'price ASC',
    'price_desc' => 'price DESC',
    'name_asc'   => 'name ASC',
    'name_desc'  => 'name DESC'
];
$orderBy = $allowed[$sort] ?? 'id DESC';

// категории
$categories = [
    "tulle"    => "Тюль",
    "curtains" => "Шторы",
    "other"    => "Другое"
];
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

            <?php foreach ($categories as $catKey => $catName): ?>
                <?php
                // номер страницы именно для этой категории
                $pageParam = "page_" . $catKey;
                $page = max(1, (int)($_GET[$pageParam] ?? 1));
                $offset = ($page - 1) * $perPage;

                // считаем всего товаров в этой категории
                $countRes = mysqli_prepare($conn, "SELECT COUNT(*) c FROM product WHERE category=?");
                mysqli_stmt_bind_param($countRes, 's', $catKey);
                mysqli_stmt_execute($countRes);
                $countResult = mysqli_stmt_get_result($countRes);
                $total = (int)mysqli_fetch_assoc($countResult)['c'];
                mysqli_stmt_close($countRes);

                $pages = max(1, (int)ceil($total / $perPage));
                ?>
                <h3 class="catalog-category"><?= $catName ?></h3>
                <div class="catalog">
                    <?php
                    $stmt = mysqli_prepare($conn, "SELECT id, name, price, image FROM product WHERE category=? ORDER BY $orderBy LIMIT ?,?");
                    mysqli_stmt_bind_param($stmt, 'sii', $catKey, $offset, $perPage);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($res) > 0):
                        while ($p = mysqli_fetch_assoc($res)): ?>
                            <div class="item">
                                <a href="product.php?id=<?= (int)$p['id'] ?>">
                                    <img src="images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                                </a>
                                <a class="title" href="product.php?id=<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a>
                                <div class="price"><?= number_format($p['price'],0,',',' ') ?> ₽</div>
                                <a class="cart-btn" href="cart.php?add=<?= (int)$p['id'] ?>">В корзину</a>
                            </div>
                        <?php endwhile;
                    else: ?>
                        <p style="color:#777;">В этой категории пока нет товаров.</p>
                    <?php endif;
                    mysqli_stmt_close($stmt);
                    ?>
                </div>

                <?php if ($pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <?php
                            // сохраняем sort и все page_* кроме текущей
                            $query = $_GET;
                            $query[$pageParam] = $i;
                            $url = '?' . http_build_query($query);
                            ?>
                            <?php if ($i == $page): ?>
                                <strong><?= $i ?></strong>
                            <?php else: ?>
                                <a href="<?= $url ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

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
