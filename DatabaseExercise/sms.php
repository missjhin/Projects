<?php
$conn = new mysqli("localhost", "root", "", "dbContacts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['save'])) {
    $studno = $_POST['studno'];
    $name = $_POST['name'];
    $cpno = $_POST['cpno'];
    $conn->query("INSERT INTO tblSMS (studno, name, cpno) VALUES ('$studno', '$name', '$cpno')");
    header("Location: ?page=view");
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $studno = $_POST['studno'];
    $name = $_POST['name'];
    $cpno = $_POST['cpno'];
    $conn->query("UPDATE tblSMS SET studno='$studno', name='$name', cpno='$cpno' WHERE sms_ID=$id");
    header("Location: ?page=view");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tblSMS WHERE sms_ID=$id");
    header("Location: ?page=view");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database - TakeHome Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>SMS Contacts</h1>
    <a href="?page=add">➕ Add New Record</a>
    <hr>

    <?php
    $page = $_GET['page'] ?? 'view';

    if ($page == 'add'): ?>
        <div class="card">
            <h2>Add Contact</h2>
            <form method="POST">
                Student No: <input type="text" name="studno" required>
                Name: <input type="text" name="name" required>
                Contact No: <input type="text" name="cpno" required>
                <input type="submit" name="save" value="Save">
            </form>
        </div>

    <?php 
        elseif ($page == 'update'):
            $id = $_GET['id'];
            $row = $conn->query("SELECT * FROM tblSMS WHERE sms_ID=$id")->fetch_assoc();
    ?>
        <div class="card">
            <h2>Update Contact</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['sms_ID']; ?>">
                Student No: <input type="text" name="studno" value="<?= $row['studno']; ?>" required>
                Name: <input type="text" name="name" value="<?= $row['name']; ?>" required>
                Contact No: <input type="text" name="cpno" value="<?= $row['cpno']; ?>" required>
                <input type="submit" name="update" value="Update">
            </form>
        </div>

    <?php else:
        $results = $conn->query("SELECT * FROM tblSMS ORDER BY sms_ID ASC");
    ?>
        <div class="card">
            <h2>All SMS Records</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student No</th>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Actions</th>
                </tr>
                <?php 
                    $counter = 1;
                    while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $counter++; ?></td>
                    <td><?= $row['studno']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['cpno']; ?></td>
                    <td>
                        <a href="?page=update&id=<?= $row['sms_ID']; ?>">✏️ Update</a> |
                        <a href="?delete=<?= $row['sms_ID']; ?>" onclick="return confirm('Delete this record?')">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
