<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect('localhost', 'root', '', 'site');
    mysqli_set_charset($conn, "utf8mb4");
    echo "✅ Подключение к MySQL прошло успешно!";
} catch (Exception $e) {
    echo "❌ Ошибка подключения: " . $e->getMessage();
}
