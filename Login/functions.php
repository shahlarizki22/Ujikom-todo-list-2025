<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "todolist";

// Koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbName);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function register($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT username FROM tb_user WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        return false;
    }

    // Cek apakah password sama
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password salah!');</script>";
        return false;
    }

    // Enkripsi password dengan MD5 (Tidak Disarankan)
    $password = hash('sha256', $password);

    // Insert user baru ke database
    mysqli_query($conn, "INSERT INTO tb_user (username, password, email) VALUES ('$username', '$password', '$email')");

    return mysqli_affected_rows($conn);
}

