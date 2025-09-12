<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = "";

// Удаление сообщения
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM messages WHERE id=$id");
    $msg = "❌ Сообщение удалено!";
}

$res = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка — Сообщения</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Сообщения из формы обратной связи</h2>

    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <!-- Кнопка выхода -->
    <p><a href="profile.php" style="color:#00a;">⬅ Выйти из админки</a></p>

    <table>
        <tr><th>ID</th><th>Имя</th><th>Email</th><th>Сообщение</th><th>Дата</th><th>Действия</th></tr>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Удалить сообщение?')">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
