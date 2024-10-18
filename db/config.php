<<<<<<< HEAD
<?php
// Database configuration
$db_host = '172.20.0.185';    // Usually 'localhost'
$db_user = 'db-admin';    // Replace with your database username
$db_pass = 'kvW6qhMQ70e';    // Replace with your database password
$db_name = 'auto_dialer';      // Replace with your database name

// Create a connection to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
=======
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
>>>>>>> b04dae7a9d44575933dc0c0f6ef591db89fab706
