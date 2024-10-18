<?php
session_start();
include_once 'db/config.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute the statement to find the user
    $stmt = $conn->prepare("SELECT username, password FROM normal_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    // Bind the result to variables
    $stmt->bind_result($db_username, $db_password);
    $stmt->fetch();

    // Check if the user exists
    if ($db_username) {
        // Verify the password
        if (password_verify($password, $db_password)) {
            // Store user data in session
            $_SESSION['username'] = $db_username;
            $_SESSION['user_type'] = 'normal';
            header("Location: user/user_dashboard.php"); // Redirect to normal user dashboard
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normal User Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Normal User Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
