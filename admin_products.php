<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = "";

// Добавление товара
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $alias = trim($_POST['alias']);
    $short = trim($_POST['short']);
    $desc = trim($_POST['desc']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);

    if ($name && $price) {
        $stmt = mysqli_prepare($conn, "INSERT INTO product (manufacturer_id, name, alias, short_description, description, price, image, meta_title, meta_keywords, meta_description) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssssssss', $name, $alias, $short, $desc, $price, $image, $name, $name, $short);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $msg = "✅ Товар добавлен!";
    }
}

// Удаление товара
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM product WHERE id=$id");
    $msg = "❌ Товар удалён!";
}

// Обновление товара
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $short = trim($_POST['short']);
    $desc = trim($_POST['desc']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);

    $stmt = mysqli_prepare($conn, "UPDATE product SET name=?, short_description=?, description=?, price=?, image=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssisi', $name, $short, $desc, $price, $image, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $msg = "✏️ Товар обновлён!";
}

// Получение товаров
$res = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка — Товары</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Управление товарами</h2>

    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <!-- Кнопка выхода -->
    <p><a href="profile.php" style="color:#00a;">⬅ Выйти из админки</a></p>

    <h3>Добавить товар</h3>
    <form method="post" style="margin-bottom:20px;">
        <input type="text" name="name" placeholder="Название">
        <input type="text" name="alias" placeholder="Алиас">
        <input type="text" name="short" placeholder="Краткое описание">
        <input type="text" name="desc" placeholder="Описание">
        <input type="number" step="0.01" name="price" placeholder="Цена">
        <input type="text" name="image" placeholder="Файл картинки (например, product19.jpg)">
        <button type="submit" name="add">Добавить</button>
    </form>

    <h3>Список товаров</h3>
    <table>
        <tr><th>ID</th><th>Название</th><th>Описание</th><th>Цена</th><th>Картинка</th><th>Действия</th></tr>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <form method="post">
                    <td><?= $row['id'] ?><input type="hidden" name="id" value="<?= $row['id'] ?>"></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><textarea name="short"><?= htmlspecialchars($row['short_description']) ?></textarea></td>
                    <td><input type="number" step="0.01" name="price" value="<?= $row['price'] ?>"></td>
                    <td>
                        <input type="text" name="image" value="<?= htmlspecialchars($row['image']) ?>">
                        <br><img src="images/<?= htmlspecialchars($row['image']) ?>" width="60">
                    </td>
                    <td>
                        <button type="submit" name="update">Обновить</button>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Удалить товар?')">Удалить</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
