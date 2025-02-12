<?php
include 'database.php'; 
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login/index.php");
    exit;
}

// Proses Insert Data
if (isset($_POST['add'])) {
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $q_insert = "INSERT INTO tasks (tasklabel, taskstatus, priority, deadline) 
                 VALUES ('$task', 'open', '$priority','$deadline')";
    mysqli_query($conn, $q_insert);
    header('Refresh:0; url=index.php');
    exit; 
}

// Proses Show Data
$q_select = "SELECT * FROM tasks ORDER BY taskid DESC";
$run_q_select = mysqli_query($conn, $q_select);

// Proses Delete Data
if (isset($_GET['delete'])) {
    $taskid = $_GET['delete'];
    $q_delete = "DELETE FROM tasks WHERE taskid = '$taskid'";
    mysqli_query($conn, $q_delete);
    header('Refresh:0; url=index.php');
}

// Proses Update Status (Open/Close)
if (isset($_GET['done'])) {
    $status = $_GET['status'] == 'open' ? 'close' : 'open';
    $taskid = $_GET['done'];
    
    $q_update = "UPDATE tasks SET taskstatus = '$status' WHERE taskid = '$taskid'";
    mysqli_query($conn, $q_update);
    header('Refresh:0; url=index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To Do List</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="sall12.css">
</head>
<body>

<div class="container">
    <div class="header">
        <div class="title">
            <i class='bx bx-sun'></i>
            <span>To Do List</span>
            <div class="logout">
                <a href="logout.php"><button>Logout</button></a>
            </div>
        </div>
        <div class="description"><?= date("l, d M Y") ?></div>
    </div>

    <div class="content">
        <div class="card">
            <form action="" method="post">
                <input type="text" name="task" class="input-control" placeholder="Add task" required>
                
                <select name="priority" class="input-control" required>
                    <option value="Rendah">Prioritas Rendah</option>
                    <option value="Tinggi">Prioritas Tinggi</option>
                </select>

                <input type="date" name="deadline" class="input-control" required>

                <div class="text-right">
                    <button type="submit" name="add">Add</button>
                </div>
            </form>
        </div>

        <?php if (mysqli_num_rows($run_q_select) > 0) {
            while ($r = mysqli_fetch_array($run_q_select)) { ?>
                <div class="card">
                    <div class="task-item <?= $r['taskstatus'] == 'close' ? 'done' : '' ?>">
                        <div>
                            <div class="">
                                <small class="priority <?= strtolower($r['priority']) == 'tinggi' ? 'high' : (strtolower($r['priority']) == 'rendah' ? 'low' : '') ?>">
                                    <?= isset($r['priority']) ? ucfirst($r['priority']) : 'N/A'; ?>
                                </small>

                                <small class="deadline">
                                    <?= isset($r['deadline']) ? date("d M Y", strtotime($r['deadline'])) : 'No Deadline'; ?>
                                </small>
                            </div>
                            <div class="task-label">
                                <input type="checkbox" onclick="window.location.href = '?done=<?= $r['taskid'] ?>&status=<?= $r['taskstatus'] ?>'" <?= $r['taskstatus'] == 'close' ? 'checked' : '' ?>>
                                <span><?= $r['tasklabel'] ?></span>
                            </div>
                        </div>
                        <div>
                            <a href="edit.php?id=<?= $r['taskid']?>" class="text-black" title="Edit"><i class="bx bx-edit"></i></a>
                            <a href="?delete=<?= $r['taskid'] ?>" class="text-black" title="Remove" onclick="return confirm('Are you sure?')"><i class="bx bx-trash"></i></a>
                        </div>
                    </div>
                </div>
        <?php }} else { ?>
            <div>Belum ada task</div>
        <?php } ?>
    </div>
</div>

</body>
</html>