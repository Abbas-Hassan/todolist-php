<?php
$db = mysqli_connect('localhost', 'root', '', 'todolist');

if (isset($_POST['submit'])){
    $task = $_POST['task'];
    if(empty($task)) {
        $errors = "You must fill in the task";
    } else {
        mysqli_query($db, "INSERT INTO todo (task) VALUES ('$task')");
        header('location: index.php');
        exit();
    }
}

if (isset($_GET['del_task'])){
    $id = $_GET['del_task'];
    mysqli_query($db, "DELETE FROM todo WHERE id=$id");
    header('location: index.php');
    exit();
}

if (isset($_GET['update'])){
    $id = $_GET['update'];
    $result = mysqli_query($db, "SELECT * FROM todo WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $taskToUpdate = $row['task'];
}

if (isset($_POST['update'])){
    $id = $_POST['id'];
    $task = $_POST['task'];
    mysqli_query($db, "UPDATE todo SET task='$task' WHERE id=$id");
    header('location: index.php');
    exit();
}

$tasks = mysqli_query($db, "SELECT * FROM todo");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>TODO LIST</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type="text/css" href='style.css'>
    <script src='main.js'></script>
</head>
<body>
    <div class="heading">
        <h2> TODO LIST </h2>
    </div>
    <form method="POST" action="index.php">
    <?php if (isset($errors)){?>
        <p><?php echo $errors;?></p>
    <?php } ?>
        <input type="text" name="task" class="task_input">
        <button type="submit" class="task_btn" name="submit">Add Task</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>S.no</th>
                <th>Task</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php 
        $i = 1;
        while ($row = mysqli_fetch_array($tasks)){?>
            <tr>
                <td><?php echo $i;?></td>
                <td class="task"><?php echo $row['task']; ?></td>
                <td class="actions">
                    <a href="index.php?del_task=<?php echo $row['id'];?>">X</a>
                    <a href="index.php?update=<?php echo $row['id'];?>">Edit</a>
                </td>
            </tr>
          <?php  $i++;} ?>
        <tbody>
        </tbody>
    </table>

    <?php if(isset($_GET['update'])): ?>
    <div class="edit-form">
        <h3>Edit Task</h3>
        <form method="POST" action="index.php">
            <input type="hidden" name="id" value="<?php echo $_GET['update']; ?>">
            <input type="text" name="task" value="<?php echo $taskToUpdate; ?>" class="task_input">
            <button type="submit" class="task_btn" name="update">Update Task</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>
