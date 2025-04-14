<?php
session_start();
require 'functions.php';

$error = false;
$loginSuccess = false;

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
        $_SESSION['user_id'] = $row['id'];
        $loginSuccess = true; // Set login success flag
    } else {
        $error = true; // Set error flag for login failure
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nih</title>
    <link rel="stylesheet" href="stylee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <div class="register-link">
                <span>Don't have an account? <a href="register.php">Register</a></span>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // PHP flags passed to JavaScript
        const loginSuccess = <?php echo json_encode($loginSuccess); ?>;
        const loginError = <?php echo json_encode($error); ?>;

        // Show notification based on login result
        if (loginSuccess) {
            alert('Login successful! Redirecting to the dashboard...');
            window.location.href = '../index.php'; // Redirect after success
        } else if (loginError) {
            alert('Login failed! Username or password is incorrect.');
        }
    </script>
</body>
</html>
