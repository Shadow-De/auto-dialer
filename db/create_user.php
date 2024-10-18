<?php
session_start();
include_once 'db/config.php';  // Adjust the path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password

    // Insert into the respective table
    if ($userType === 'admin') {
        $insert_user = "INSERT INTO admin_users (username, password) VALUES ('$username', '$password')";
    } else {
        $insert_user = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    }

    if ($conn->query($insert_user) === TRUE) {
        echo "New $userType user created successfully!";
    } else {
        echo "Error creating user: " . $conn->error;
    }
}

// Redirect back to dashboard
header("Location: admin_dashboard.php");
exit();
?>
