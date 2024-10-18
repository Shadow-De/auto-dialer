<<<<<<< HEAD
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
=======
<?php
session_start();
include_once 'db/config.php'; // Ensure this path is correct

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the connection to the database is established
    if (!$conn) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the statement to find the user
    $stmt = $conn->prepare("SELECT * FROM normal_users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Display error if preparation fails
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['username'] = $user['username'];
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
>>>>>>> b04dae7a9d44575933dc0c0f6ef591db89fab706
