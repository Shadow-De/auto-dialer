<?php
// Include the database configuration file
include_once 'config.php';

// Function to delete a user by ID
function deleteUser($conn, $userId, $userType) {
    if ($userType === 'admin') {
        $stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM normal_users WHERE id = ?");
    }

    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }

    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        return true; // User deleted successfully
    } else {
        return "Error deleting user: " . $stmt->error;
    }
}
?>
