<?php
session_start();

/* Многомерный массив с товарами (демонстрационный) */
$products = [
  ['name'=>'Шторы классические','price'=>12000,'desc'=>'Классический стиль'],
  ['name'=>'Тюль ажурный','price'=>6000,'desc'=>'Ажурный рисунок'],
  ['name'=>'Шторы велюровые','price'=>15000,'desc'=>'Мягкий велюр'],
  ['name'=>'Тюль с узором','price'=>7000,'desc'=>'Лёгкий узор'],
  ['name'=>'Портьеры blackout','price'=>18000,'desc'=>'Максимальное затемнение'],
];

$search_q = '';
$results = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $search_q = trim($_POST['search_q'] ?? '');
    $first = mb_substr($search_q, 0, 1, 'UTF-8');
    if ($first !== '') {
        foreach ($products as $p) {
            if (mb_stripos($p['name'], $first, 0, 'UTF-8') === 0) {
                $results[] = $p;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Демо-поиск по первой букве — Стерх</title>
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
      </ul>
    </aside>

    <main class="content">
      <h2>Поиск по первой букве (демо)</h2>
      <form method="post" style="max-width:420px;">
        <input type="search" name="search_q" placeholder="Введите первую букву названия" value="<?= htmlspecialchars($search_q) ?>">
        <button type="submit">🔍</button>
      </form>

      <?php if ($search_q !== ''): ?>
        <h3 style="margin-top:16px;">Результаты:</h3>
        <?php if (!$results): ?>
          <p>Ничего не найдено.</p>
        <?php else: ?>
          <?php foreach ($results as $r): ?>
            <div style="padding:8px 0; border-bottom:1px solid #eee;">
              <b><?= htmlspecialchars($r['name']) ?></b> — <?= number_format($r['price'],0,',',' ') ?> ₽
              <div><?= htmlspecialchars($r['desc']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php endif; ?>
    </main>

    <aside class="image">
      <img src="images/banner.jpg" alt="Баннер">
    </aside>
  </div>

  <?php include "footer.php"; ?>
</div>
</body>
</html>
