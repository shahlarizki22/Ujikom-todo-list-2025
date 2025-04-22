<?php
session_start();
require 'functions.php';

if (isset($_POST["register"])) {
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if (strlen($password) < 8) {
        echo "<script>alert('Password harus terdiri dari minimal 8 karakter');</script>";
    } elseif ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak cocok');</script>";
    } else {
        if (register($_POST) > 0) {
            echo "<script>alert('User baru berhasil ditambahkan');</script>";
            $_SESSION["login"] = true;
            $_SESSION["username"] = $_POST["username"];
            echo "<script>
                    alert('User baru berhasil ditambahkan');
                    window.location.href = 'login.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Registrasi gagal');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Register</h1>
            <div class="input-box">
                <input name="username" type="text" placeholder="Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input name="email" type="email" placeholder="Email" required>
                <i class="fa fa-envelope-o"></i>
            </div>
            <div class="input-box">
                <input name="password" type="password" placeholder="Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="input-box">
                <input name="password2" type="password" placeholder="Confirm Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
        </form>
    </div>
</body>
</html>
