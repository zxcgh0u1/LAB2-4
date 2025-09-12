<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "site";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
