<?php
// Database configuration
$db_host = 'localhost';    // Usually 'localhost'
$db_user = 'root';    // Replace with your database username
$db_pass = '';    // Replace with your database password
$db_name = 'auto_dialer';      // Replace with your database name

// Create a connection to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
