<?php
include 'database.php';
session_start();

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login/index.php"); 
    exit;
}

$userid = $_SESSION['user_id'];

// Proses Insert Data Task
if (isset($_POST['add'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    $q_insert = "INSERT INTO tasks (tasklabel, taskstatus, priority, deadline, userid) 
                 VALUES ('$task', 'open', '$priority', '$deadline', '$userid')";
    mysqli_query($conn, $q_insert);
    header('Location: index.php');
    exit;
}

// Proses Delete Task
if (isset($_GET['delete'])) {
    $taskid = intval($_GET['delete']);
    $q_delete = "DELETE FROM tasks WHERE taskid = '$taskid'";
    mysqli_query($conn, $q_delete);
    header('Location: index.php');
    exit;
}

// Proses Show Data (Hanya yang belum selesai)
$q_select = "SELECT * FROM tasks WHERE taskstatus = 'open' AND userid = '$userid' ORDER BY taskid DESC";
$run_q_select = mysqli_query($conn, $q_select);
if (!$run_q_select) {
    die("Query Error: " . mysqli_error($conn));
}

// Proses Update Status Task (Open/Close)
if (isset($_GET['done'])) {
    $taskid = intval($_GET['done']);
    $status = ($_GET['status'] === 'open') ? 'close' : 'open';
    
    // Ambil data task sebelum diperbarui
    $task_query = "SELECT * FROM tasks WHERE taskid = '$taskid'";
    $task_result = mysqli_query($conn, $task_query);
    $task_data = mysqli_fetch_assoc($task_result);

    if ($task_data) {
        // Masukkan data ke tabel history jika status berubah menjadi close
        if ($status === 'close') {
           $q_history = "INSERT INTO history (tasklabel, status, priority, deadline, updated_at, userid)
              VALUES (
                  '" . mysqli_real_escape_string($conn, $task_data['tasklabel']) . "',
                  '$status',
                  '" . mysqli_real_escape_string($conn, $task_data['priority']) . "',
                  '" . $task_data['deadline'] . "',
                  NOW(),
                  '$userid'
              )";
            mysqli_query($conn, $q_history);
        }
        
        // Update status task tanpa menghapusnya
        $q_update = "UPDATE tasks SET taskstatus = '$status' WHERE taskid = '$taskid'";
        mysqli_query($conn, $q_update);
    }
    
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To Do List</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="salaa22.css">
</head>
<body>

<div class="container">
    <div class="header">
        <div class="title">
            <i class='bx bx-sun'></i>
            <span>To Do List</span>
            <div class="logout">
                <a href="history.php"><button>History</button></a>
                <a href="logout.php"><button>Logout</button></a>
            </div>
        </div>
        <div class="description"><?= date("l, d M Y") ?></div>
    </div>

    <div class="content">
        <!-- Form Tambah Task -->
        <div class="card-satu">
            <form action="" method="post">
                <input type="text" name="task" class="input-control" placeholder="Add task" required>
                <select name="priority" class="input-control" required>
                    <option value="Rendah">Prioritas Rendah</option>
                    <option value="Sedang">Prioritas Sedang</option>
                    <option value="Tinggi">Prioritas Tinggi</option>
                </select>
                <input type="date" name="deadline" class="input-control" required>
                <div class="text-right">
                    <button type="submit" name="add">Add</button>
                </div>
            </form>
        </div>

        <!-- Tampilkan Task -->
        <?php if (mysqli_num_rows($run_q_select) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($run_q_select)): ?>
                <div class="card" onclick="window.location.href='subtasks.php?taskid=<?= $r['taskid'] ?>'">
                    <div class="task-item <?= $r['taskstatus'] === 'close' ? 'done' : '' ?>">
                        <div>
                            <div class="task-label">
                                <input type="checkbox" onclick="event.stopPropagation(); window.location.href = '?done=<?= $r['taskid'] ?>&status=<?= $r['taskstatus'] ?>'" <?= $r['taskstatus'] === 'close' ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($r['tasklabel']) ?></span>
                            </div>
                        </div>
                        <div class="task-details">
                        <small class="priority 
                                <?= strtolower($r['priority']) === 'tinggi' ? 'high' : (strtolower($r['priority']) === 'sedang' ? 'medium' : 'low') ?>">
                                <?= ucfirst($r['priority']) ?>
                            </small>
                            <small class="deadline"><?= date("d M Y", strtotime($r['deadline'])) ?></small>
                            <a href="edit.php?id=<?= $r['taskid'] ?>" class="text-black" onclick="event.stopPropagation();"><i class="bx bx-edit"></i></a>
                            <a href="?delete=<?= $r['taskid'] ?>" class="text-black" onclick="event.stopPropagation(); return confirm('Are you sure?')"><i class="bx bx-trash"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div>Belum ada task</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>