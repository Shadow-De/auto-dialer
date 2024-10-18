<<<<<<< HEAD
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Include the database configuration file
include_once '../db/config.php';

// Initialize variables
$user_id = $_GET['id'] ?? null;
$user_data = null;

if ($user_id) {
    // Fetch the user data
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ? UNION SELECT * FROM normal_users WHERE id = ?");
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    // Handle form submission for updating user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updated_username = trim($_POST['update_username']);
        $updated_password = password_hash(trim($_POST['update_password']), PASSWORD_DEFAULT);
        $updated_user_type = $_POST['update_user_type'];

        // Update user in the correct table
        if ($user_data['user_type'] === 'admin') {
            $update_stmt = $conn->prepare("UPDATE admin_users SET username = ?, password = ? WHERE id = ?");
        } else {
            $update_stmt = $conn->prepare("UPDATE normal_users SET username = ?, password = ? WHERE id = ?");
        }
        $update_stmt->bind_param("ssi", $updated_username, $updated_password, $user_id);

        if ($update_stmt->execute()) {
            header("Location: admin_dashboard.php?success=User updated successfully!");
            exit();
        } else {
            $error_message = "Error updating user: " . $update_stmt->error;
        }
    }
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
        <h2>Edit User</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($user_data): ?>
            <form method="POST">
                <label for="update_username">Username:</label>
                <input type="text" name="update_username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>

                <label for="update_password">Password:</label>
                <input type="password" name="update_password" required>

                <label for="update_user_type">User Type:</label>
                <select name="update_user_type" required>
                    <option value="normal" <?php echo ($user_data['user_type'] === 'normal') ? 'selected' : ''; ?>>Normal User</option>
                    <option value="admin" <?php echo ($user_data['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin User</option>
                </select>

                <button type="submit">Update User</button>
            </form>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
=======
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Include the database configuration file
include_once '../db/config.php';

// Initialize variables
$user_id = $_GET['id'] ?? null;
$user_data = null;

if ($user_id) {
    // Fetch the user data
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ? UNION SELECT * FROM normal_users WHERE id = ?");
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    // Handle form submission for updating user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updated_username = trim($_POST['update_username']);
        $updated_password = password_hash(trim($_POST['update_password']), PASSWORD_DEFAULT);
        $updated_user_type = $_POST['update_user_type'];

        // Update user in the correct table
        if ($user_data['user_type'] === 'admin') {
            $update_stmt = $conn->prepare("UPDATE admin_users SET username = ?, password = ? WHERE id = ?");
        } else {
            $update_stmt = $conn->prepare("UPDATE normal_users SET username = ?, password = ? WHERE id = ?");
        }
        $update_stmt->bind_param("ssi", $updated_username, $updated_password, $user_id);

        if ($update_stmt->execute()) {
            header("Location: admin_dashboard.php?success=User updated successfully!");
            exit();
        } else {
            $error_message = "Error updating user: " . $update_stmt->error;
        }
    }
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
        <h2>Edit User</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($user_data): ?>
            <form method="POST">
                <label for="update_username">Username:</label>
                <input type="text" name="update_username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>

                <label for="update_password">Password:</label>
                <input type="password" name="update_password" required>

                <label for="update_user_type">User Type:</label>
                <select name="update_user_type" required>
                    <option value="normal" <?php echo ($user_data['user_type'] === 'normal') ? 'selected' : ''; ?>>Normal User</option>
                    <option value="admin" <?php echo ($user_data['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin User</option>
                </select>

                <button type="submit">Update User</button>
            </form>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
>>>>>>> b04dae7a9d44575933dc0c0f6ef591db89fab706
