<?php
session_start();
require 'functions.php';

$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = hash('sha256', $_POST["password"]); 

    $stmt = mysqli_prepare($conn, "SELECT * FROM tb_user WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit;
    }

    $error = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesal.css">
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Login</h1>
            <?php if ($error): ?>
                <p style="color: red; text-align: center;">Username atau password salah!</p>
            <?php endif; ?>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <div class="textregister">
    <span>Don't have an account? <a href="register.php">Register</a></span>
</div>
        </form>
    </div>
</body>
</html>