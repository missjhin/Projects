<?php
session_start();

$filename = 'users.txt';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (file_exists($filename)) {
        $users = file($filename, FILE_IGNORE_NEW_LINES);

        foreach ($users as $user) {
            list($savedUser, $savedPass) = explode(':', $user);
            if ($savedUser === $username && $savedPass === $password) {
                $_SESSION['username'] = $username;
                setcookie("username", $username, time() + 3600); 
                header("Location: welcome.php");
                exit;
            }
        }

        $message = "Invalid username or password.";
    } else {
        $message = "No registered users. Please sign up first.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login User</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit" class="login-btn">Log In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
