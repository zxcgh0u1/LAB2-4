<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$pid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($pid > 0) {
    $check = mysqli_query($conn, "SELECT id FROM cart WHERE user_id=$user_id AND product_id=$pid");
    if ($check && mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity=quantity+1 WHERE user_id=$user_id AND product_id=$pid");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $pid, 1)");
    }
}

/* Возврат на страницу, с которой пришли */
$back = $_SERVER['HTTP_REFERER'] ?? "catalog.php";
header("Location: $back");
exit;
