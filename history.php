<?php
include 'database.php';
session_start();

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login/index.php");
    exit;
}

$userid = $_SESSION['user_id'];

// Ambil data dari tabel history
$q_history = "SELECT * FROM history WHERE userid = '$userid' ORDER BY updated_at DESC";
$run_q_history = mysqli_query($conn, $q_history);
if (!$run_q_history) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>git 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="salaa22.css">
</head>
<body>

<div class="container">
    <div class="header">
        <div class="title">
            <i class='bx bx-history'></i>
            <span>History</span>
        </div>
        <div class="logout">
            <a href="index.php"><button>Back</button></a>
        </div>
    </div>

    <div class="content">
     <!-- Header Kolom -->
     <div class="card header-card">
            <div class="task-item">
                <div class="task-label"><strong>No</strong></div>
                <div class="task-label"><strong>Task</strong></div>
                <div class="task-details">
                    <strong>Priority</strong>
                    <strong>Deadline</strong>
                    <strong>Closed Time</strong>
                </div>
            </div>
        </div>
        <!-- Tampilkan History -->
       <?php if (mysqli_num_rows($run_q_history) > 0): ?>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($run_q_history)): ?>
                <div class="card">
                    <div class="task-item">
                        <div class="task-label">
                            <span><?= $no++ ?></span>
                        </div>
                        <div class="task-label">
                            <span><?= htmlspecialchars($r['tasklabel']) ?></span>
                        </div>
                        <div class="task-details">
                            <small class="priority <?= strtolower($r['priority']) === 'tinggi' ? 'high' : 'low' ?>">
                                <?= ucfirst($r['priority']) ?>
                            </small>
                            <small class="deadline"> <?= date("d M Y", strtotime($r['deadline'])) ?> </small>
                            <small><?= date("d M Y, H:i", strtotime($r['updated_at'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div>Belum ada history</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>