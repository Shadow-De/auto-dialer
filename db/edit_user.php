<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Include the database configuration file
include_once '../db/config.php';

// Fetch user details for editing
if (isset($_GET['id']) && isset($_GET['user_type'])) {
    $userId = $_GET['id'];
    $userType = $_GET['user_type'];

    // Get user information based on user type
    if ($userType == 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM normal_users WHERE id = ?");
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Update user details
if (isset($_POST['update_user'])) {
    $updated_username = trim($_POST['updated_username']);
    $updated_password = password_hash(trim($_POST['updated_password']), PASSWORD_DEFAULT);
    $updated_user_type = $_POST['user_type'];

    // Update the user in the correct table
    if ($userType == 'admin') {
        $stmt = $conn->prepare("UPDATE admin_users SET username = ?, password = ? WHERE id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE normal_users SET username = ?, password = ? WHERE id = ?");
    }

    $stmt->bind_param("ssi", $updated_username, $updated_password, $userId);

    if ($stmt->execute()) {
        $success_message = "User updated successfully!";
        // Update the user type if it's changed
        if ($userType !== $updated_user_type) {
            if ($updated_user_type === 'admin') {
                // Move the user from normal_users to admin_users
                $stmt_move = $conn->prepare("INSERT INTO admin_users (username, password) SELECT username, password FROM normal_users WHERE id = ?");
                $stmt_move->bind_param("i", $userId);
                $stmt_move->execute();
                $stmt_delete = $conn->prepare("DELETE FROM normal_users WHERE id = ?");
                $stmt_delete->bind_param("i", $userId);
                $stmt_delete->execute();
            } else {
                // Move the user from admin_users to normal_users
                $stmt_move = $conn->prepare("INSERT INTO normal_users (username, password) SELECT username, password FROM admin_users WHERE id = ?");
                $stmt_move->bind_param("i", $userId);
                $stmt_move->execute();
                $stmt_delete = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt_delete->bind_param("i", $userId);
                $stmt_delete->execute();
            }
        }
    } else {
        $error_message = "Error updating user: " . $stmt->error;
    }
}

// Fetch user details again for display after update
if (isset($userId)) {
    if ($userType == 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM normal_users WHERE id = ?");
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit User</h1>
        </header>

        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="user_type" value="<?php echo htmlspecialchars($user['user_type']); ?>">

            <label for="updated_username">Change Username:</label>
            <input type="text" name="updated_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="updated_password">Change Password:</label>
            <input type="password" name="updated_password" required>

            <label for="user_type">Change User Type:</label>
            <select name="user_type" required>
                <option value="normal" <?php echo ($user['user_type'] === 'normal') ? 'selected' : ''; ?>>Normal User</option>
                <option value="admin" <?php echo ($user['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin User</option>
            </select>

            <button type="submit" name="update_user">Update User</button>
        </form>

        <a href="../admin/admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
