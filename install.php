<?php
// Подключение к MySQL
$mysqli = new mysqli("localhost", "root", "", "");
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Создаём базу (если её нет)
$mysqli->query("CREATE DATABASE IF NOT EXISTS site
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci");
$mysqli->select_db("site");

// Читаем SQL из файла site.sql
$sql = file_get_contents(__DIR__ . "/site.sql");
if (!$sql) {
    die("❌ Ошибка: не удалось прочитать файл site.sql");
}

// Выполняем весь SQL
if ($mysqli->multi_query($sql)) {
    while ($mysqli->more_results() && $mysqli->next_result()) {;}
    echo "<h2 style='color:green'>✅ Установка завершена!</h2>
          <p>База <b>site</b> восстановлена полностью (структура + данные).</p>";
} else {
    echo "<h2 style='color:red'>❌ Ошибка выполнения SQL:</h2>" . $mysqli->error;
}
?>
