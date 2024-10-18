<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

// Include the database configuration file
include_once '../db/config.php';
include_once '../db/delete_user.php'; // Include the delete user function

// Function to get the total number of admin users
function getUserCounts($conn) {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM admin_users");
    return $stmt->fetch_assoc()['count'];
}

// Function to get all users
function getAllUsers($conn) {
    $stmt = $conn->query("
        SELECT id, username, 'admin' AS user_type FROM admin_users 
        UNION 
        SELECT id, username, 'normal' AS user_type FROM normal_users
    ");
    return $stmt->fetch_all(MYSQLI_ASSOC);
}

// Create a new user
if (isset($_POST['create_user'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? UNION SELECT * FROM normal_users WHERE username = ?");
    
    if (!$check_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param("ss", $new_username, $new_username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error_message = "Username already exists. Please choose another.";
    } else {
        // Insert new user into the correct table
        if ($user_type == 'admin') {
            $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO normal_users (username, password) VALUES (?, ?)");
        }

        // Error handling for prepare
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $new_username, $new_password);

        if ($stmt->execute()) {
            $success_message = "User created successfully!";
        } else {
            $error_message = "Error creating user: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Check for delete user request
if (isset($_GET['delete_user_id']) && isset($_GET['user_type'])) {
    $userId = $_GET['delete_user_id'];
    $userType = $_GET['user_type'];

    $deleteResult = deleteUser($conn, $userId, $userType);
    if ($deleteResult === true) {
        $success_message = "User deleted successfully!";
    } else {
        $error_message = $deleteResult; // Error message from delete function
    }
}

$userCount = getUserCounts($conn);
$allUsers = getAllUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Total Admin Users: <?php echo $userCount; ?></p>
            </header>

            <section class="card">
                <h2>Create New User</h2>
                <?php if (isset($success_message)): ?>
                    <div class="alert success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="alert error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <label for="new_username">Username:</label>
                    <input type="text" name="new_username" required>
                    
                    <label for="new_password">Password:</label>
                    <input type="password" name="new_password" required>
                    
                    <label for="user_type">User Type:</label>
                    <select name="user_type" required>
                        <option value="normal">Normal User</option>
                        <option value="admin">Admin User</option>
                    </select>
                    
                    <button type="submit" name="create_user">Create User</button>
                </form>
            </section>

            <section class="card">
                <h2>Manage Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>User Type</th>
                            <th>Actions</th> <!-- New column for actions -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                                <td>
                                    <a href="../db/edit_user.php?id=<?php echo $user['id']; ?>&user_type=<?php echo $user['user_type']; ?>">
                                        <i class="fas fa-edit"></i> <!-- Edit icon -->
                                    </a>
                                    <a href="?delete_user_id=<?php echo $user['id']; ?>&user_type=<?php echo $user['user_type']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>
