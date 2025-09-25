<?php
require_once "header.php";
require_once "db.php";

$username = $fullname = $email = $phone = $password = $confirm_password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error = "Все поля обязательны для заполнения.";
    } elseif ($password !== $confirm_password) {
        $error = "Пароли не совпадают.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username=? OR email=?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($res) > 0) {
            $error = "Пользователь с таким логином или email уже существует.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, fullname, email, phone, password, role) VALUES (?,?,?,?,?,'user')");
            mysqli_stmt_bind_param($stmt, "sssss", $username, $fullname, $email, $phone, $hash);
            mysqli_stmt_execute($stmt);
            header("Location: login.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<main class="content">
    <div class="auth-form">
        <h2>Регистрация</h2>
        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Имя пользователя (логин)" value="<?= htmlspecialchars($username) ?>" required>
            <input type="text" name="fullname" placeholder="ФИО" value="<?= htmlspecialchars($fullname) ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
            <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($phone) ?>" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
</main>

<div style="height:171px;"></div>

<?php require_once "footer.php"; ?>
