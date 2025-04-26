<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db = 'todoList';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
        $stmt->bind_param("s", $task);
        $stmt->execute();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tasks WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['done'])) {
    $id = intval($_GET['done']);
    $conn->query("UPDATE tasks SET is_done=1 WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$tasks = $conn->query("SELECT * FROM tasks ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>📝 Todo List</h2>

    <form method="POST">
        <input type="text" name="task" placeholder="Enter a task..." required>
        <button type="submit" name="add">Add</button>
    </form>

    <ul>
        <?php while ($row = $tasks->fetch_assoc()): ?>
            <li>
                <span class="<?= $row['is_done'] ? 'done' : '' ?>">
                    <?= htmlspecialchars($row['task']) ?>
                </span>
                <span class="actions">
                    <?php if (!$row['is_done']): ?>
                        <a href="?done=<?= $row['id'] ?>">✅</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')">❌</a>
                </span>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
