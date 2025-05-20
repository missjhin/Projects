<?php
session_start();

$filename = 'users.txt';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!file_exists($filename)) {
        file_put_contents($filename, "");
    }

    $users = file($filename, FILE_IGNORE_NEW_LINES);
    $found = false;

    foreach ($users as $user) {
        list($savedUser, $savedPass) = explode(':', $user);
        if ($savedUser === $username) {
            $found = true;
            break;
        }
    }

    if ($found) {
        $message = "Username already exists. Please choose another.";
    } else {
        file_put_contents($filename, "$username:$password\n", FILE_APPEND);
        $message = "Registered successfully. <a href='login.php'>Log in now</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Sign in / Register</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit" class="login-btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
</body>
</html>
