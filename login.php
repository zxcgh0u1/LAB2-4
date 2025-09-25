<?php
require_once "header.php";
require_once "db.php";

$username = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Введите логин и пароль.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Неверный логин или пароль.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<main class="content">
    <div class="auth-form">
        <h2>Вход</h2>
        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Логин или email" value="<?= htmlspecialchars($username) ?>" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="submit" value="Войти">
        </form>
    </div>
</main>

<div style="height:343px;"></div>

<?php require_once "footer.php"; ?>
