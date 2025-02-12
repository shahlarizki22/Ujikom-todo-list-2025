<?php
session_start();
require 'functions.php';

if (isset($_POST["register"])) {
    if (register($_POST) > 0) {
        echo "<script>alert('User baru berhasil ditambahkan');</script>";
        $_SESSION["login"] = true;
        $_SESSION["username"] = $_POST["username"];
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Registrasi gagal');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylesal.css">
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Register</h1>
            <div class="input-box">
                <input name="username" type="text" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input name="email" type="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input name="password" type="password" placeholder="Password" required>
            </div>
            <div class="input-box">
                <input name="password2" type="password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
        </form>
    </div>
</body>
</html>
