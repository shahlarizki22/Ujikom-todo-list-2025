<?php
include 'database.php';
session_start();


if (!isset($_SESSION['login'])) {
    header("Location: login/index.php");
    exit;
}

// task ID dari URL
$taskid = $_GET['taskid'] ?? null;

if (!$taskid) {
    header("Location: index.php");
    exit;
}

//  detail tugas
$q_task = "SELECT * FROM tasks WHERE taskid = '$taskid'";
$task = mysqli_fetch_assoc(mysqli_query($conn, $q_task));

// Dapatkan subtasks
$q_subtasks = "SELECT * FROM subtasks WHERE taskid = '$taskid'";
$subtasks = mysqli_query($conn, $q_subtasks);

// Tambah subtask
if (isset($_POST['add_subtask'])) {
    $subtask = $_POST['subtask'];
     
    $q_insert_subtask = "INSERT INTO subtasks (taskid, subtasklabel, subtaskstatus) 
                         VALUES ('$taskid', '$subtask', 'open')";
    mysqli_query($conn, $q_insert_subtask);
    header("Location: subtasks.php?taskid=$taskid");
    exit;
}

// Update status subtask
if (isset($_GET['subtask_done'])) {
    $status = $_GET['status'] == 'open' ? 'close' : 'open';
    $subtaskid = $_GET['subtask_done'];
    $q_update_subtask = "UPDATE subtasks SET subtaskstatus = '$status' WHERE subtaskid = '$subtaskid'";
    mysqli_query($conn, $q_update_subtask);
    header("Location: subtasks.php?taskid=$taskid");
    exit;
}

// Hapus subtask
if (isset($_GET['delete_subtask'])) {
    $subtaskid = $_GET['delete_subtask'];
    $q_delete_subtask = "DELETE FROM subtasks WHERE subtaskid = '$subtaskid'";
    mysqli_query($conn, $q_delete_subtask);
    header("Location: subtasks.php?taskid=$taskid");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subtasks for <?= $task['tasklabel'] ?></title>
    <link rel="stylesheet" href="salaa22.css">
   <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
   
</head>
<body>

<div class="container">
    <h1>Subtasks for "<?= $task['tasklabel'] ?>"</h1>
    <a href="index.php"> < Back to Tasks</a>
    <i class="fa-solid fa-arrow-left"></i> 
    <form method="post">
        <input type="text" name="subtask" class="input-control" placeholder="Add subtask" required>
        <button type="submit" name="add_subtask">Add Subtask</button>
    </form>
    <div class="card">
        <?php if (mysqli_num_rows($subtasks) > 0) {
            while ($subtask = mysqli_fetch_array($subtasks)) { ?>
                <div class="subtask-item <?= $subtask['subtaskstatus'] == 'close' ? 'done' : '' ?>">
                    <input 
                        type="checkbox" 
                        onclick="window.location.href = '?taskid=<?= $taskid ?>&subtask_done=<?= $subtask['subtaskid'] ?>&status=<?= $subtask['subtaskstatus'] ?>'" 
                        <?= $subtask['subtaskstatus'] == 'close' ? 'checked' : '' ?>
                    >
                    <span><?= $subtask['subtasklabel'] ?></span>

                    

                    <a href="?taskid=<?= $taskid ?>&delete_subtask=<?= $subtask['subtaskid'] ?>" 
                     onclick="return confirm('Delete this subtask?')">
                        <i class="bx bx-trash"></i>
                    </a>
            
                </div>
            <?php }
        } else { ?>
            <div>No subtasks available</div>
        <?php } ?>
        </div>
</div>

</body>
</html>
